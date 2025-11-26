<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', config('app.locale', 'ar'));

        if (! in_array($locale, ['en', 'ar'], true)) {
            $locale = 'ar';
            session(['locale' => $locale]);
        }

        App::setLocale($locale);

        view()->share('locale', $locale);
        view()->share('isRtl', $locale === 'ar');

        return $next($request);
    }
}
