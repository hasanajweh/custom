<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMainAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $network = $request->route('network');

        if (! $user || ! $user->isMainAdmin()) {
            abort(403);
        }

        if ($network && $user->network_id !== $network->id) {
            abort(403, 'Network mismatch.');
        }

        return $next($request);
    }
}
