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
        $networkParam = $request->route('network');
        $network = $networkParam instanceof Network
            ? $networkParam
            : Network::where('slug', $networkParam)->first();

        if (! $network) {
            abort(404, 'Network not found');
        }

        $request->attributes->set('network', $network);
        Application::instance(Network::class, $network);
        view()->share('network', $network);
        URL::defaults(['network' => $network->slug]);

        return $next($request);
    }
}
