<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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

    public function schools(): HasMany
    {
        return $this->hasMany(School::class, 'network_id');
    }

    public function mainAdmins(): HasMany
    {
        return $this->hasMany(User::class, 'network_id')
            ->where('is_main_admin', true);
    }

    public function schoolUsers(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            School::class,
            'network_id',   // School.network_id
            'school_id',    // User.school_id
            'id',           // Network.id
            'id'            // School.id
        );
    }

    public function allUsers(bool $withTrashed = false)
    {
        $mainAdmins = $withTrashed
            ? $this->mainAdmins()->withTrashed()->get()
            : $this->mainAdmins()->get();

        $schoolUsers = $withTrashed
            ? $this->schoolUsers()->withTrashed()->get()
            : $this->schoolUsers()->get();

        return $mainAdmins->merge($schoolUsers);
    }

    public function mainAdmin(): HasOne
    {
        return $this->hasOne(User::class, 'network_id')->where('is_main_admin', true);
    }
}
