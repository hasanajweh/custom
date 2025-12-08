<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Get locale from session, default to 'ar'
        $locale = Session::get('locale', config('app.locale', 'ar'));
        
        // Ensure locale is valid
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }
        
        // Set the application locale
        app()->setLocale($locale);
        
        return $next($request);
    }
}
