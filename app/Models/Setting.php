<?php
// app/Models/Setting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Clear the settings cache
     */



    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $settings = static::getAllCached();
        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        static::clearCache();
        return $setting;
    }

    /**
     * Get all settings (cached)
     */
    public static function getAllCached()
    {
        return Cache::remember('app_settings', 3600, function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get public settings only (cached)
     */
    public static function getPublicCached()
    {
        return Cache::remember('public_settings', 3600, function () {
            return static::where('is_public', true)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Get settings by group
     */
    public static function getByGroup($group)
    {
        return static::where('group', $group)->get();
    }

    /**
     * Cast value based on type
     */
    public function getCastedValueAttribute()
    {
        switch ($this->type) {
            case 'boolean':
                return filter_var($this->value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $this->value;
            case 'float':
                return (float) $this->value;
            case 'json':
                return json_decode($this->value, true);
            case 'array':
                return explode(',', $this->value);
            default:
                return $this->value;
        }
    }

    /**
     * Set value with proper casting
     */
    public function setCastedValueAttribute($value)
    {
        switch ($this->type) {
            case 'boolean':
                $this->value = $value ? '1' : '0';
                break;
            case 'json':
                $this->value = json_encode($value);
                break;
            case 'array':
                $this->value = is_array($value) ? implode(',', $value) : $value;
                break;
            default:
                $this->value = (string) $value;
        }
    }
}
