<?php

namespace App\Http\Middleware;

use App\Support\SchoolResolver;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use App\Models\School;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Don't redirect if expecting JSON (API requests)
        if ($request->expectsJson()) {
            return null;
        }

        // If trying to access Super Admin pages, redirect to Super Admin login
        if ($request->is('superadmin/*') || $request->is('superadmin')) {
            return route('superadmin.login');
        }

        // If the route has a school parameter, redirect to that school's login
        $school = SchoolResolver::resolve($request->route('school'));

        if ($school && $school->network) {
            return tenant_route('login', $school);
        }

        // ⭐ SECURITY FIX: Don't redirect to random school
        // Instead, show 404 or redirect to landing page
        // Users must have the correct school URL

        // Option 1: Redirect to landing page (recommended)
        return route('landing');

        // Option 2: Return null to show 404 (uncomment if you prefer)
        // return null;
    }
}
