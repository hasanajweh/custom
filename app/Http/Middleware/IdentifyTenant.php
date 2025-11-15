<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get the school slug string from the route parameter (e.g., "aJw").
        $parameter = $request->route('school');
        $schoolSlug = is_string($parameter) ? $parameter : null;

        // This is necessary because sometimes Laravel passes the full model object here.
        if ($parameter instanceof School) {
            $schoolSlug = $parameter->slug;
        }

        if ($schoolSlug) {
            // 2. Find the full School model object from the database.
            // Note: firstOrFail() will automatically show a 404 page if the school doesn't exist.
            $school = School::where('slug', $schoolSlug)->firstOrFail();

            // 3. Put the actual School OBJECT into the service container.
            app()->singleton(School::class, fn () => $school);
        }

        return $next($request);
    }
}
