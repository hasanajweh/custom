<?php

namespace App\Http\Middleware;

use App\Support\SchoolResolver;
use Closure;
use Illuminate\Http\Request;
use App\Models\School;

class ValidateSchoolAccess
{
    public function handle(Request $request, Closure $next)
    {
        $school = $request->route('school');
        $school = SchoolResolver::resolve($request->route('school'));
        $user = $request->user();

        // If school parameter exists
        if ($school) {
            // Ensure school is active
            if ($school instanceof School) {
                if (!$school->is_active) {
                    abort(403, 'This school is currently inactive.');
                }

                if ($user && $user->role === 'main_admin') {
                    if ($user->network_id !== $school->network_id) {
                        abort(403, 'You do not manage this network.');
                    }
                } elseif ($user && !$user->is_super_admin && $user->school_id !== $school->id) {
                    abort(403, 'You do not have access to this school.');
                }

                // ✅ ONLY check subscription for ADMINS, skip for supervisors and teachers
                if ($user && $user->role === 'admin' && !$school->hasActiveSubscription() && !$user->is_super_admin) {
                    if ($request->routeIs('school.admin.plan-management.*')) {
                        return $next($request);
                    }

                    return redirect()->to(tenant_route('dashboard', $school))
                        ->with('warning', 'School subscription is not active. Please contact your administrator.');
                }
            }
        }

        return $next($request);
    }
}
