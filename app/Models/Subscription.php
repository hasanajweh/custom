<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'school_id',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Get the school that owns this subscription
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the plan for this subscription
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->ends_at > now();
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->ends_at < now();
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiryAttribute(): int
    {
        return now()->diffInDays($this->ends_at, false);
    }

    /**
     * Check if subscription is expiring soon (within 30 days)
     */
    public function isExpiringSoon(): bool
    {
        $daysLeft = $this->days_until_expiry;
        return $daysLeft >= 0 && $daysLeft <= 30;
    }

    /**
     * Activate the subscription and the school
     */
    public function activate(): void
    {
        $this->update(['status' => 'active']);
        $this->school->update(['is_active' => true]);
    }

    /**
     * Pause the subscription
     */
    public function pause(): void
    {
        $this->update(['status' => 'paused']);
    }

    /**
     * Resume a paused subscription
     */
    public function resume(): void
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Cancel the subscription and deactivate school
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
        $this->school->update(['is_active' => false]);
    }

    /**
     * Extend subscription by months
     */
    public function extend(int $months): void
    {
        $this->update([
            'ends_at' => $this->ends_at->addMonths($months),
        ]);
    }

    /**
     * Get formatted price based on subscription duration
     */
    public function getPriceAttribute(): float
    {
        $months = $this->starts_at->diffInMonths($this->ends_at);

        if ($months >= 12) {
            return $this->plan->price_annually / 100;
        }

        return $this->plan->price_monthly / 100;
    }
}
