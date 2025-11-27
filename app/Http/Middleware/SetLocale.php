<?php

namespace App\Http\Middleware;

use Closure;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = session('locale', 'ar');
        app()->setLocale($locale);
        return $next($request);
    }
}
