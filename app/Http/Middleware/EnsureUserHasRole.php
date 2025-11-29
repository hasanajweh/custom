<?php

namespace App\Http\Middleware;

use App\Services\ActiveContext;
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

        $activeRole = ActiveContext::getRole();

        if (! $activeRole) {
            $activeRole = $user->role;
        }

        $allowedRoles = $this->normalizeRoles($roles);

        if (in_array('admin', $allowedRoles, true) && $user->role === 'main_admin') {
            return $next($request);
        }

        if (empty($allowedRoles) || ! in_array($activeRole, $allowedRoles, true)) {
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
