<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in AND has the is_super_admin flag set to true
        if (! $request->user() || ! $request->user()->is_super_admin) {
            abort(403, 'Unauthorized Action');
        }
        return $next($request);
    }
}
