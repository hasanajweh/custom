<?php
// app/Models/CustomNotification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'username',
        'file_title',
        'grade',
        'subject',
        'submission_type',
        'plan_type',
        'file_submission_id',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fileSubmission()
    {
        return $this->belongsTo(FileSubmission::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function getNotificationMessageAttribute()
    {
        if ($this->submission_type === 'plan') {
            return "{$this->username} uploaded a {$this->plan_type} plan: \"{$this->file_title}\"";
        }

        return "{$this->username} uploaded \"{$this->file_title}\" - {$this->submission_type} for Grade {$this->grade}, {$this->subject}";
    }
}
