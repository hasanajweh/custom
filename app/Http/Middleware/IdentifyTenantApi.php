<?php

namespace App\Http\Middleware;

use App\Models\Network;
use App\Models\School;
use App\Support\SchoolResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as Application;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenantApi
{
    /**
     * Handle an incoming API request and set tenant context.
     * This works with headers or request body for network/school identification.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Try to get network and school from headers first (for API consistency)
        $networkSlug = $request->header('X-Network') ?? $request->input('network');
        $schoolSlug = $request->header('X-School') ?? $request->input('school');

        if ($networkSlug && $schoolSlug) {
            $network = Network::where('slug', $networkSlug)->first();
            
            if ($network) {
                $school = School::where('slug', $schoolSlug)
                    ->where('network_id', $network->id)
                    ->first();

                if ($school) {
                    Application::instance(School::class, $school);
                    Application::instance(Network::class, $network);
                    
                    // Add to request attributes for easy access
                    $request->attributes->set('network', $network);
                    $request->attributes->set('school', $school);
                }
            }
        }

        return $next($request);
    }
}
