<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Set locale from session
        $locale = Session::get('locale', config('app.locale'));

        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
        }
    }
}
