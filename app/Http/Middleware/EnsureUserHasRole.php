<?php
namespace App\Http\Middleware;
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

        if (empty($allowedRoles) || ! in_array($user->role, $allowedRoles, true)) {
            abort(403);
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