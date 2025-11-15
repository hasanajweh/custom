<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
{
    /**
     * Sanitize all input data
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                // Remove any potential XSS attempts
                $value = strip_tags($value);
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                // Remove any NULL bytes
                $value = str_replace(chr(0), '', $value);
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
