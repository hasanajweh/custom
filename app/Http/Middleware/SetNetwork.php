<?php

namespace App\Http\Middleware;

use App\Models\Network;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as Application;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetNetwork
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $networkParam = $request->route('network');
            
            if (! $networkParam) {
                \Log::warning('SetNetwork middleware: No network parameter in route', [
                    'route' => $request->route()?->getName(),
                    'url' => $request->url(),
                ]);
                abort(404, 'Network parameter is required');
            }

            $network = $networkParam instanceof Network
                ? $networkParam
                : Network::where('slug', $networkParam)->first();

            if (! $network) {
                \Log::warning('SetNetwork middleware: Network not found', [
                    'slug' => $networkParam,
                    'route' => $request->route()?->getName(),
                ]);
                abort(404, 'Network not found');
            }
        } catch (\Exception $e) {
            \Log::error('SetNetwork middleware exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            abort(500, 'Error setting network context');
        }

        $request->attributes->set('network', $network);
        Application::instance(Network::class, $network);
        view()->share('network', $network);
        URL::defaults(['network' => $network->slug]);

        return $next($request);
    }
}
