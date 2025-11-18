<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class School extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'network_id',
        'city',
        'is_active',
        'storage_limit',
        'storage_used',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'storage_limit' => 'integer',
        'storage_used' => 'integer',
    ];

    public function isActiveWithNetwork(): bool
    {
        return $this->is_active || (bool) $this->network?->is_active;
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    /**
     * Get all subscriptions for this school
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the active subscription
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->latest();
    }

    /**
     * Get all users belonging to this school
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all file submissions for this school
     */
    public function fileSubmissions(): HasMany
    {
        return $this->hasMany(FileSubmission::class);
    }

    /**
     * Get all subjects for this school
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get all grades for this school
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Check if school has an active subscription
     */
    public function hasActiveSubscription(): bool
    {
        if ($this->network?->is_active) {
            return true;
        }

        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->exists();
    }

    /**
     * Get storage used in human-readable format
     */
    public function getStorageUsedFormattedAttribute(): string
    {
        $bytes = $this->storage_used;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get storage limit in human-readable format
     */
    public function getStorageLimitFormattedAttribute(): string
    {
        $bytes = $this->storage_limit;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get storage usage percentage
     */
    public function getStorageUsedPercentageAttribute(): float
    {
        if ($this->storage_limit == 0) {
            return 0;
        }

        return min(round(($this->storage_used / $this->storage_limit) * 100, 2), 100);
    }

    /**
     * Get the current plan through active subscription
     */
    public function getCurrentPlanAttribute()
    {
        return $this->activeSubscription?->plan;
    }
}
