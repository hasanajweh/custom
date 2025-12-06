<?php

namespace App\Http\Middleware;

use App\Services\ActiveContext;
use App\Support\SchoolResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $allowedRoles = $this->normalizeRoles($roles);

        $activeSchool = ActiveContext::getSchool();
        $activeRole = ActiveContext::getRole();

        $routeSchool = SchoolResolver::resolve($request->route('school') ?? $request->route('branch'));

        if ($activeSchool && $routeSchool && $activeSchool->id !== $routeSchool->id) {
            return redirect()->to(tenant_route('dashboard', $activeSchool));
        }

        if ($activeSchool && $activeRole) {
            $hasRole = $user->schoolUserRoles()
                ->where('school_id', $activeSchool->id)
                ->where('role', $activeRole)
                ->exists();

            if ($hasRole) {
                if (empty($allowedRoles) || in_array($activeRole, $allowedRoles, true)) {
                    return $next($request);
                }

                abort(403, 'You do not have the required role for this context.');
            }

            ActiveContext::clear();
            $activeSchool = null;
            $activeRole = null;
        }

        if (! $activeSchool) {
            if ($routeSchool && $user->schoolUserRoles()->where('school_id', $routeSchool->id)->exists()) {
                ActiveContext::setSchool($routeSchool->id);
                $activeSchool = $routeSchool;
            }
        }

        if (! $activeSchool) {
            abort(403, 'No active school context available.');
        }

        if (! $activeRole) {
            $roleQuery = $user->schoolUserRoles()->where('school_id', $activeSchool->id);

            if (! empty($allowedRoles)) {
                $roleQuery->whereIn('role', $allowedRoles);
            }

            $derivedRole = $roleQuery->value('role');

            if ($derivedRole) {
                try {
                    ActiveContext::setRole($derivedRole);
                } catch (\Throwable $e) {
                    // Ignore and fall through to abort if setting fails
                }

                $activeRole = $derivedRole;
            }
        }

        if (! $activeRole) {
            abort(403, 'You do not have an active role for this context.');
        }

        if (! empty($allowedRoles) && ! in_array($activeRole, $allowedRoles, true)) {
            abort(403, 'You do not have the required role for this context.');
        }

        return $next($request);
    }

    /**
     * Normalize the accepted roles into a flat array.
     *
     * @param  array<int, string>  $roles
     * @return array<int, string>
     */
    protected function normalizeRoles(array $roles): array
    {
        $allowed = [];

        foreach ($roles as $role) {
            foreach (preg_split('/[|,]/', $role) as $segment) {
                $segment = trim($segment);

                if ($segment !== '') {
                    $allowed[] = $segment;
                }
            }
        }

        return array_values(array_unique($allowed));
    }
}
