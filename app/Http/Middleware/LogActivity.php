<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use App\Services\ActivityLoggerService;
use Throwable;

class LogActivity
{
public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->user() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            try {
                ActivityLoggerService::log(
                    description: sprintf('%s %s', $request->method(), $request->path()),
                    school: $request->user()->school,
                    user: $request->user(),
                    logName: 'http_requests',
                    properties: [
                        'action' => $request->method(),
                        'url' => $request->fullUrl(),
                        'response_code' => $response->getStatusCode(),
                        'success' => $response->isSuccessful(),
                    ],
                    event: 'http.request'
                );
            } catch (Throwable $e) {
                Log::error('Failed to log activity', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $response;
    }
