<?php

namespace App\Http\Middleware;

use App\Logging\SecurityLogger;
use App\Models\Network;
use App\Support\SchoolResolver;
use App\Services\TenantContext;
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

        $userSchools = $user?->schoolUserRoles()->pluck('school_id')->toArray();
        $availableRoles = $user?->schoolUserRoles()
            ->where('school_id', $school->id)
            ->pluck('role')
            ->toArray();

        if (! $user || ! in_array($school->id, $userSchools) || empty($availableRoles)) {
            SecurityLogger::logTenantIsolationBreach(
                (int) $school->id,
                $user?->school_id ?? 0,
            );

            return redirect()
                ->to(safe_tenant_route('login', $school))
                ->with('error', __('messages.auth.unauthorized'));
        }

        $activeRole = null;
        $sessionRole = session('active_role');

        if ($sessionRole && in_array($sessionRole, $availableRoles)) {
            $activeRole = $sessionRole;
        } elseif ($user->role && in_array($user->role, $availableRoles)) {
            $activeRole = $user->role;
        } else {
            $activeRole = collect(['admin', 'supervisor', 'teacher'])
                ->first(fn ($role) => in_array($role, $availableRoles))
                ?? $availableRoles[0];
        }

        TenantContext::setActiveContext($school->id, $activeRole);
        $user->setAttribute('role', $activeRole);
        $user->setAttribute('school_id', $school->id);

        return $next($request);
    }
}
