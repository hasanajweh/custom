<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    // Add default values here
    public string $site_name = 'aJw';
    public int $default_storage_limit = 50;
    public string $currency_symbol = '$';

    public static function group(): string
    {
        return 'general';
    }
}
