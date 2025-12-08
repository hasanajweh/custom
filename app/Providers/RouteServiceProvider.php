<?php
// app/Providers/RouteServiceProvider.php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Models\Network;
use App\Models\School;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Route registration is now handled in bootstrap/app.php (Laravel 11)
        // Only route model bindings are defined here

        Route::bind('network', function ($value) {
            return Network::where('slug', $value)->firstOrFail();
        });

        Route::bind('branch', function ($value) {
            $network = request()->route('network');

            $query = School::query()->where('slug', $value);

            if ($network instanceof Network) {
                $query->where('network_id', $network->id);
            } elseif (is_string($network)) {
                $networkModel = Network::where('slug', $network)->first();

                if ($networkModel) {
                    $query->where('network_id', $networkModel->id);
                }
            }

            return $query->firstOrFail();
        });

        // Backward compatibility for existing `school` parameter usage
        Route::bind('school', function ($value) {
            $network = request()->route('network');

            $query = School::query()->where('slug', $value);

            if ($network instanceof Network) {
                $query->where('network_id', $network->id);
            } elseif (is_string($network)) {
                $networkModel = Network::where('slug', $network)->first();

                if ($networkModel) {
                    $query->where('network_id', $networkModel->id);
                }
            }

            return $query->firstOrFail();
        });

        // Bind supervisor parameter to User model, scoped to current school
        Route::bind('supervisor', function ($value) {
            $query = \App\Models\User::where('id', $value)
                ->where('role', 'supervisor');
            
            // If we have a school/branch in the route, scope to it
            $school = request()->route('school') ?? request()->route('branch');
            if ($school instanceof School) {
                $query->where('school_id', $school->id);
            }
            
            return $query->firstOrFail();
        });
    }
}
