<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // If the user is a super admin, always redirect to the super admin dashboard.
                if ($user->is_super_admin) {
                    return redirect(route('superadmin.dashboard'));
                }

                // Otherwise, it's a regular user, redirect to their school's dashboard.
                $school = $user->school;

                if ($school) {
                    return redirect(tenant_dashboard_route($school, $user));
                }
            }
        }

        return $next($request);
    }
}
