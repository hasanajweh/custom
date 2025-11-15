<?php

namespace App\Models;

use App\Services\ActivityLoggerService;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToTenant, LogsActivity, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'school_id',
        'network_id',
        'subject',
        'grade',
        'is_super_admin',
        'is_main_admin',
        'is_active',
        'deactivated_at',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
            'is_main_admin' => 'boolean',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'deactivated_at' => 'datetime',
        ];
    }

    /**
     * Configure activity logging
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role', 'school_id', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "User {$this->name} was {$eventName}");
    }

    /**
     * Boot method for additional logging
     */
    protected static function booted()
    {
        static::created(function ($user) {
            if ($user->school_id && class_exists('\App\Services\ActivityLoggerService')) {
                try {
                    ActivityLoggerService::log(
                        "New user created: {$user->name} ({$user->role})",
                        $user->school,
                        auth()->user(),
                        'user_management',
                        ['user_role' => $user->role]
                    );
                } catch (\Exception $e) {
                    \Log::warning('Activity logging failed for user creation', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        });

        static::updated(function ($user) {
            if ($user->school_id && class_exists('\App\Services\ActivityLoggerService')) {
                try {
                    // Log role/email changes
                    if ($user->wasChanged(['role', 'email'])) {
                        ActivityLoggerService::log(
                            "User updated: {$user->name}",
                            $user->school,
                            auth()->user(),
                            'user_management',
                            ['changes' => $user->getChanges()]
                        );
                    }

                    // Log activation/deactivation
                    if ($user->wasChanged('is_active')) {
                        $status = $user->is_active ? 'activated' : 'deactivated';
                        ActivityLoggerService::log(
                            "User {$status}: {$user->name} ({$user->role})",
                            $user->school,
                            auth()->user(),
                            'user_management',
                            ['user_status' => $status]
                        );
                    }
                } catch (\Exception $e) {
                    \Log::warning('Activity logging failed for user update', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        });

        static::deleted(function ($user) {
            if ($user->school_id && class_exists('\App\Services\ActivityLoggerService')) {
                try {
                    // Check if it's soft delete (archive) or force delete
                    $action = $user->isForceDeleting() ? 'permanently deleted' : 'archived';
                    ActivityLoggerService::log(
                        "User {$action}: {$user->name} ({$user->role})",
                        $user->school,
                        auth()->user(),
                        'user_management',
                        ['deleted_user_role' => $user->role, 'action' => $action]
                    );
                } catch (\Exception $e) {
                    \Log::warning('Activity logging failed for user deletion', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        });

        static::restored(function ($user) {
            if ($user->school_id && class_exists('\App\Services\ActivityLoggerService')) {
                try {
                    ActivityLoggerService::log(
                        "User restored: {$user->name} ({$user->role})",
                        $user->school,
                        auth()->user(),
                        'user_management',
                        ['user_role' => $user->role]
                    );
                } catch (\Exception $e) {
                    \Log::warning('Activity logging failed for user restoration', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        });
    }

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }

    public function grades(): BelongsToMany
    {
        return $this->belongsToMany(Grade::class)
            ->withPivot('school_id')
            ->withTimestamps();
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class)
            ->withPivot('school_id')
            ->withTimestamps();
    }

    public function fileSubmissions(): HasMany
    {
        return $this->hasMany(FileSubmission::class);
    }

    public function supervisorSubjects(): HasMany
    {
        return $this->hasMany(SupervisorSubject::class, 'supervisor_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    public function scopeSupervisors($query)
    {
        return $query->where('role', 'supervisor');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    // Role checking methods
    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }

    public function isMainAdmin(): bool
    {
        return $this->role === 'main_admin' || $this->is_main_admin;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    // Activation/Deactivation methods
    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function activate()
    {
        $this->update([
            'is_active' => true,
            'deactivated_at' => null,
        ]);
    }

    public function deactivate()
    {
        $this->update([
            'is_active' => false,
            'deactivated_at' => now(),
        ]);
    }

    // Utility methods
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    public function syncTeacherGrades(array $gradeIds, int $schoolId): void
    {
        if (!$this->isTeacher()) {
            throw new \LogicException('Only teachers can be assigned grades.');
        }

        $payload = collect($gradeIds)
            ->unique()
            ->mapWithKeys(fn ($id) => [$id => ['school_id' => $schoolId]])
            ->all();

        $this->grades()->sync($payload);
    }

    public function syncTeacherSubjects(array $subjectIds, int $schoolId): void
    {
        if (!$this->isTeacher()) {
            throw new \LogicException('Only teachers can be assigned subjects.');
        }

        $payload = collect($subjectIds)
            ->unique()
            ->mapWithKeys(fn ($id) => [$id => ['school_id' => $schoolId]])
            ->all();

        $this->subjects()->sync($payload);
    }
}
