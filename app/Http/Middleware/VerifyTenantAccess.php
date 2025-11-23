<?php

namespace App\Http\Middleware;

use App\Logging\SecurityLogger;
use App\Models\Network;
use App\Support\SchoolResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTenantAccess
{
   

    public function handle(Request $request, Closure $next): Response
    {
        $schoolParam = $request->route('school') ?? $request->route('branch');
        $school = SchoolResolver::resolve($schoolParam);
        $network = $request->route('network');
        $user = $request->user();

        // If no school in route, continue
        if (! $school) {
            return $next($request);
        }

        if ($user && $user->is_super_admin) {
            return $next($request);
        }

        $networkId = $network instanceof Network ? $network->id : null;

        if ($user && $user->role === 'main_admin') {
            if ($networkId && $user->network_id !== $networkId) {
                abort(403, 'Unauthorized access to this network.');
            }

            if ($school && $school->network_id !== $user->network_id) {
                abort(403, 'Unauthorized access to this school.');
            }

            return $next($request);
        }

        if ($network && $school && $school->network_id !== $networkId) {
            abort(404);
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
