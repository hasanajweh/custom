<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter as Limiter;

class RateLimiter
{
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if (Limiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.'
            ], 429);
        }

        Limiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        $response->headers->set(
            'X-RateLimit-Limit', $maxAttempts
        );

        $response->headers->set(
            'X-RateLimit-Remaining',
            Limiter::remaining($key, $maxAttempts)
        );

        return $response;
    }

    protected function resolveRequestSignature(Request $request)
    {
        return sha1(
            $request->method() .
            '|' . $request->server('SERVER_NAME') .
            '|' . $request->path() .
            '|' . $request->ip()
        );
    }
}
