<?php

namespace App\Http\Middleware;

use App\Logging\SecurityLogger;
use App\Support\SchoolResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTenantAccess
{
   

    public function handle(Request $request, Closure $next): Response
    {
        $school = SchoolResolver::resolve($request->route('school'));
        $user = $request->user();

        // If no school in route, continue
        if (! $school) {

            return $next($request);    
            }

        if ($user && $user->is_super_admin) {
            return $next($request);
        }


        if (! $user || $user->school_id !== $school->id) {
            SecurityLogger::logTenantIsolationBreach(
                (int) $school->id,
                $user?->school_id ?? 0,
            );

            abort(403, 'Unauthorized access to this school.');
        }

        return $next($request);
    }
}
