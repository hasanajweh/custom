<?php

namespace App\Http\Middleware;

use App\Models\Network;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMainAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isMainAdmin()) {
            abort(403);
        }

        $networkParam = $request->route('network');
        $networkSlug = $networkParam instanceof Network ? $networkParam->slug : $networkParam;

        if ($networkSlug && $user->network?->slug !== $networkSlug) {
            abort(403, 'Unauthorized access to this network.');
        }

        return $next($request);
    }
}
