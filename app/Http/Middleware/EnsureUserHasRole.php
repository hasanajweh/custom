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

        // Main admin exception: if they have active context set, allow access
        if ($user->isMainAdmin()) {
            $activeSchool = ActiveContext::getSchool();
            $activeRole = ActiveContext::getRole();
            
            if ($activeSchool && $activeRole) {
                // Verify school belongs to main admin's network
                if ($activeSchool->network_id === $user->network_id) {
                    // Check if the active role matches the required role for this route
                    $allowedRoles = $this->normalizeRoles($roles);
                    if (empty($allowedRoles) || in_array($activeRole, $allowedRoles, true)) {
                        return $next($request);
                    }
                }
            }
        }

        $allowedRoles = $this->normalizeRoles($roles);

        $route = $request->route();
        $routeName = $route?->getName();
        $routeParameters = $route?->parameters() ?? [];
        $routeSchoolParam = $route?->parameter('branch') ?? $route?->parameter('school');
        $slugFromRoute = optional($routeSchoolParam)?->slug ?? $routeSchoolParam;

        // Get active context from session (user's selected school/role)
        $activeSchool = ActiveContext::getSchool();
        $activeRole = ActiveContext::getRole();

        // If we have both active school and role from session, validate and use them
        if ($activeSchool && $activeRole) {
            $hasRole = $user->schoolUserRoles()
                ->where('school_id', $activeSchool->id)
                ->where('role', $activeRole)
                ->exists();

            if ($hasRole) {
                // Check if URL slugs match ActiveContext - if not, redirect to correct URL
                if ($slugFromRoute && $activeSchool->slug !== $slugFromRoute && $routeName) {
                    $params = collect($routeParameters)->except(['branch', 'school', 'network'])->all();

                    return redirect()->to(tenant_route(
                        $routeName,
                        $activeSchool,
                        $params,
                    ));
                }

                // Check if the active role is allowed for this route
                if (empty($allowedRoles) || in_array($activeRole, $allowedRoles, true)) {
                    return $next($request);
                }

                // Active role doesn't match route requirements - clear it and derive new one
                ActiveContext::clearRole();
                $activeRole = null;
            } else {
                // User doesn't have this role in this school - clear context
                ActiveContext::clear();
                $activeSchool = null;
                $activeRole = null;
            }
        }

        // If no active school, try to get from route
        if (! $activeSchool) {
            $schoolFromRoute = $request->route('school') ?? $request->route('branch');
            $resolvedSchool = SchoolResolver::resolve($schoolFromRoute);

            if ($resolvedSchool && $user->schoolUserRoles()->where('school_id', $resolvedSchool->id)->exists()) {
                ActiveContext::setSchool($resolvedSchool->id);
                $activeSchool = $resolvedSchool;
            }
        }

        if (! $activeSchool) {
            abort(403, 'No active school context available.');
        }

        // If we have an active role, validate it's still valid
        if ($activeRole) {
            $hasRole = $user->schoolUserRoles()
                ->where('school_id', $activeSchool->id)
                ->where('role', $activeRole)
                ->exists();

            if (! $hasRole) {
                ActiveContext::clearRole();
                $activeRole = null;
            }
        }

        // Only derive a role if we don't have one (don't override user's selection)
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

        // Final check: ensure URL slugs match ActiveContext
        if ($slugFromRoute && $activeSchool->slug !== $slugFromRoute && $routeName) {
            $params = collect($routeParameters)->except(['branch', 'school', 'network'])->all();

            return redirect()->to(tenant_route(
                $routeName,
                $activeSchool,
                $params,
            ));
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
