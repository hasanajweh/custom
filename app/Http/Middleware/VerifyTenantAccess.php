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
        $activeSchoolId = session('active_school_id');
        $activeRole = session('active_role');

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

        if ($school && (! $user || ! in_array($school->id, $userSchools))) {
            SecurityLogger::logTenantIsolationBreach(
                (int) $school->id,
                $user?->school_id ?? 0,
            );

            return redirect()
                ->to(safe_tenant_route('login', $school))
                ->with('error', __('messages.auth.unauthorized'));
        }

        $effectiveSchoolId = null;

        if ($activeSchoolId && in_array($activeSchoolId, $userSchools ?? [])) {
            $effectiveSchoolId = $activeSchoolId;
        } elseif ($school && in_array($school->id, $userSchools ?? [])) {
            $effectiveSchoolId = $school->id;
        } elseif ($user?->school_id && in_array($user->school_id, $userSchools ?? [])) {
            $effectiveSchoolId = $user->school_id;
        } elseif (! empty($userSchools)) {
            $effectiveSchoolId = $userSchools[0];
        }

        if (! $effectiveSchoolId) {
            return redirect()
                ->to(route('login'))
                ->with('error', __('messages.auth.unauthorized'));
        }

        $availableRoles = $user?->schoolUserRoles()
            ->where('school_id', $effectiveSchoolId)
            ->pluck('role')
            ->toArray();

        if (empty($availableRoles)) {
            return redirect()
                ->to(route('login'))
                ->with('error', __('messages.auth.unauthorized'));
        }

        if ($activeRole && in_array($activeRole, $availableRoles)) {
            $resolvedRole = $activeRole;
        } elseif ($user?->role && in_array($user->role, $availableRoles)) {
            $resolvedRole = $user->role;
        } else {
            $resolvedRole = collect(['admin', 'supervisor', 'teacher'])
                ->first(fn ($role) => in_array($role, $availableRoles))
                ?? $availableRoles[0];
        }

        TenantContext::setActiveContext($effectiveSchoolId, $resolvedRole);

        return $next($request);
    }
}
