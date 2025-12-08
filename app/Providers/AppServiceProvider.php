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
use Illuminate\Support\Facades\Auth;
use App\Services\ActiveContext;

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
        // File size limits removed - unlimited file size
        // Set PHP upload limits to a very high value (10GB) to allow large files
        if (function_exists('ini_set')) {
            @ini_set('upload_max_filesize', '10240M'); // 10GB
            @ini_set('post_max_size', '10240M'); // 10GB
        }

        View::composer('*', function ($view) {
            if (app()->has(School::class)) {
                $view->with('school', app(School::class));
            }

            $user = Auth::user();

            if ($user) {
                $contexts = $user->availableContexts();
                $activeSchool = ActiveContext::getSchool();
                $activeRole   = ActiveContext::getRole();

                $view->with('availableContexts', $contexts);
                $view->with('activeContextSchool', $activeSchool);
                $view->with('activeContextRole', $activeRole);
            }
        });
        
        Event::listen(Login::class, LogSuccessfulLogin::class);
        Event::listen(Failed::class, LogFailedLogin::class);
    }
}
