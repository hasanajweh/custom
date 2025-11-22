<?php

namespace App\Http\Middleware;

use App\Support\SchoolResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $school = SchoolResolver::resolve($request->route('school'));

            $loginRoute = ($school && $school->network)
                ? safe_tenant_route('login', $school)
                : route('landing');

            return redirect()->to($loginRoute)
                ->with('error', 'Your account has been deactivated. Please contact your school administrator.');
        }

        return $next($request);
    }
}
