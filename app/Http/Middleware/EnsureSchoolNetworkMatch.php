<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolNetworkMatch
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $network = $request->route('network');
        $school = $request->route('school');

        if ($network && $school && $school->network_id !== $network->id) {
            abort(404);
        }

        return $next($request);
    }
}
