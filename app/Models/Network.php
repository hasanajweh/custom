<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Network extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'plan_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function branches(): HasMany
    {
        return $this->hasMany(School::class, 'network_id');
    }

    public function mainAdmin(): HasOne
    {
        return $this->hasOne(User::class, 'network_id')->where('role', 'main_admin');
    }
}
