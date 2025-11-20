<?php

namespace App\Http\Middleware;

use App\Models\School;
use App\Support\SchoolResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as Application;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $school = SchoolResolver::resolve($request->route('school'), true);

        if ($school) {
            $school->loadMissing('network');
            Application::instance(School::class, $school);
            view()->share('school', $school);
            $branch = $request->attributes->get('branch');
            if (! $branch) {
                $request->attributes->set('branch', $school);
            }

            if ($school->network) {
                Application::instance(\App\Models\Network::class, $school->network);
                view()->share('network', $school->network);
                URL::defaults([
                    'network' => $school->network->slug,
                    'school' => $school->slug,
                ]);
            }
        }

        return $next($request);
    }
}
