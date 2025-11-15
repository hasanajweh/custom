<?php

namespace App\Providers;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View; // <-- Add this import
use Illuminate\Support\ServiceProvider;
use App\Listeners\LogFailedLogin;
use App\Listeners\LogSuccessfulLogin;
use App\Models\School;               // <-- Add this import
use App\Services\CacheService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $maxUploadSizeMb = config('uploads.max_size_mb', 100);
        $uploadLimit = $maxUploadSizeMb . 'M';

        if (function_exists('ini_set')) {
            @ini_set('upload_max_filesize', $uploadLimit);
            @ini_set('post_max_size', $uploadLimit);

            $currentMemoryLimit = ini_get('memory_limit');
            if ($currentMemoryLimit !== false && $currentMemoryLimit !== '-1') {
                $numericLimit = (int) filter_var($currentMemoryLimit, FILTER_SANITIZE_NUMBER_INT);
                $requiredLimit = $maxUploadSizeMb * 2;

                if ($numericLimit > 0 && $numericLimit < $requiredLimit) {
                    @ini_set('memory_limit', $requiredLimit . 'M');
                }
            }
        }

        View::composer('*', function ($view) {
            if (app()->has(School::class)) {
                $view->with('school', app(School::class));
            }
        });
        
        Event::listen(Login::class, LogSuccessfulLogin::class);
        Event::listen(Failed::class, LogFailedLogin::class);
    }
}
