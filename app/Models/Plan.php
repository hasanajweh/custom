<?php
// app/Models/Plan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price_monthly',
        'price_annually',
        'storage_limit_in_gb',
        'features',
        'is_active',
    ];

    protected $casts = [
        'price_monthly' => 'integer',
        'price_annually' => 'integer',
        'storage_limit_in_gb' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'features' => '[]',
    ];

    // Accessors for backward compatibility
    public function getPriceAttribute()
    {
        // Return monthly price by default
        return $this->price_monthly;
    }

    public function getBillingCycleAttribute()
    {
        return 'monthly'; // Default billing cycle
    }

    // Get formatted prices
    public function getFormattedPriceMonthlyAttribute()
    {
        return '$' . number_format($this->price_monthly / 100, 2);
    }

    public function getFormattedPriceAnnuallyAttribute()
    {
        return '$' . number_format($this->price_annually / 100, 2);
    }

    // Get storage limit in bytes
    public function getStorageLimitInBytesAttribute()
    {
        return $this->storage_limit_in_gb * 1024 * 1024 * 1024;
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
