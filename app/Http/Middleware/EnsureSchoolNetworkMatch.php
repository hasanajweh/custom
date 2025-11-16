<?php

namespace App\Http\Middleware;

use App\Models\Network;
use App\Support\SchoolResolver;
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
        $school = SchoolResolver::resolve($request->route('school'));

        $networkModel = $network instanceof Network
            ? $network
            : Network::where(
                is_numeric($network) ? 'id' : 'slug',
                $network
            )->first();

        if ($networkModel && $school && $school->network_id !== $networkModel->id) {
            abort(404);
        }

        return $next($request);
    }
}
