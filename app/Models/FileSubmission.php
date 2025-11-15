<?php
// app/Models/FileSubmission.php
// COMPLETE MODEL WITH ACCESSOR FIX

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'original_filename',
        'file_size',
        'mime_type',
        'file_hash',
        'subject_id',
        'grade_id',
        'submission_type',
        'plan_type',
        'user_id',
        'school_id',
        'status',
        'rejection_reason',
        'download_count',
        'last_accessed_at',
    ];

    protected $casts = [
        'last_accessed_at' => 'datetime',
        'file_size' => 'integer',
        'download_count' => 'integer',
        'deleted_at' => 'datetime',
    ];

    // Add these to the appends array so they're always available
    protected $appends = ['subject_name', 'grade_name'];

    // ===================================
    // RELATIONSHIPS
    // ===================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Keep the original relationships (they might work in some contexts)
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }

    // ===================================
    // ACCESSOR FIX - THIS IS THE KEY SOLUTION
    // ===================================

    /**
     * Get subject name directly from database
     * This bypasses the relationship system entirely
     */
    public function getSubjectNameAttribute()
    {
        // If no subject_id, return null
        if (!$this->subject_id) {
            return null;
        }

        // Use static cache to avoid multiple queries for the same subject
        static $subjectCache = [];

        // Check if we already have this subject cached
        if (!isset($subjectCache[$this->subject_id])) {
            // Query directly without any global scopes
            $subject = \DB::table('subjects')
                ->where('id', $this->subject_id)
                ->first();

            $subjectCache[$this->subject_id] = $subject ? $subject->name : null;
        }

        return $subjectCache[$this->subject_id];
    }

    /**
     * Get grade name directly from database
     * This bypasses the relationship system entirely
     */
    public function getGradeNameAttribute()
    {
        // If no grade_id, return null
        if (!$this->grade_id) {
            return null;
        }

        // Use static cache to avoid multiple queries for the same grade
        static $gradeCache = [];

        // Check if we already have this grade cached
        if (!isset($gradeCache[$this->grade_id])) {
            // Query directly without any global scopes
            $grade = \DB::table('grades')
                ->where('id', $this->grade_id)
                ->first();

            $gradeCache[$this->grade_id] = $grade ? $grade->name : null;
        }

        return $gradeCache[$this->grade_id];
    }

    // ===================================
    // ADDITIONAL HELPER ACCESSORS
    // ===================================

    /**
     * Get subject data as object (id and name)
     */
    public function getSubjectDataAttribute()
    {
        if (!$this->subject_id) {
            return null;
        }

        return (object) [
            'id' => $this->subject_id,
            'name' => $this->subject_name
        ];
    }

    /**
     * Get grade data as object (id and name)
     */
    public function getGradeDataAttribute()
    {
        if (!$this->grade_id) {
            return null;
        }

        return (object) [
            'id' => $this->grade_id,
            'name' => $this->grade_name
        ];
    }

    // ===================================
    // HELPER METHODS (Keep all your existing ones)
    // ===================================

    public function isPlanType()
    {
        return in_array($this->submission_type, ['daily_plan', 'weekly_plan', 'monthly_plan']);
    }

    public function scopeForTeacher($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForSupervisor($query, $subjectIds)
    {
        return $query->whereIn('subject_id', $subjectIds)
            ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan']);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['user', 'school']);
        // Removed subject and grade from here since we're using accessors
    }

    public function scopeAcademic($query)
    {
        return $query->whereIn('submission_type', ['exam', 'worksheet', 'summary']);
    }

    public function scopePlans($query)
    {
        return $query->whereIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan']);
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 2) . ' KB';
        if ($bytes < 1073741824) return round($bytes / 1048576, 2) . ' MB';
        return round($bytes / 1073741824, 2) . ' GB';
    }
}
