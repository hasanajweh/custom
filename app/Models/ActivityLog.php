<?php
// app/Models/ActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    use HasFactory;
    
    protected $table = 'activity_log';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'school_id',
        'action',
        'url',
        'ip_address',
        'user_agent',
        'response_code',
        'description',
        'context',
        'success',   
     ];

     /**
     * @var array<string, string>
     */
    protected $casts = [
        'properties' => 'array',
        'context' => 'array',
        'success' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    public function getActionAttribute(): ?string
    {
        return $this->properties['action'] ?? null;
    }

    public function getUrlAttribute(): ?string
    {
        return $this->properties['url'] ?? null;
    }

    public function getIpAddressAttribute(): ?string
    {
        return $this->properties['ip_address'] ?? null;
    }

    public function getUserAgentAttribute(): ?string
    {
        return $this->properties['user_agent'] ?? null;
    }

    public function getResponseCodeAttribute(): ?int
    {
        return $this->properties['response_code'] ?? null;
    }

    public function getSuccessAttribute(): ?bool
    {
        return $this->properties['success'] ?? null;
    }

    public function getContextAttribute(): mixed
    {
        return $this->properties['context'] ?? null;
    }
}

    