<?php

namespace App\Http\Middleware;

use App\Logging\SecurityLogger;
use App\Models\Network;
use App\Models\SchoolUserRole;
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

        if ($network && $school && $school->network_id !== $networkId) {
            abort(404);
        }

        if ($user && $user->role === 'main_admin') {
            if ($networkId && $user->network_id !== $networkId) {
                abort(403, 'Unauthorized access to this network.');
            }

            if ($school && $school->network_id !== $user->network_id) {
                abort(403, 'Unauthorized access to this school.');
            }

            return $next($request);
        }

        if (! $user) {
            abort(403, 'Unauthorized.');
        }

        $hasContext = SchoolUserRole::where('user_id', $user->id)
            ->where('school_id', $school?->id)
            ->exists();

        if (! $hasContext) {
            SecurityLogger::logTenantIsolationBreach(
                (int) ($school?->id ?? 0),
                $user->school_id ?? 0,
            );

            abort(403, 'Access denied to this school.');
        }

        return $next($request);
    }
}
