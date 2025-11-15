<?php

namespace App\Listeners;

use App\Services\ActivityLoggerService;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class LogSuccessfulLogin
{
    public function __construct(private readonly Request $request)
    {
    }

    public function handle(Login $event): void
    {
        $user = $event->user;

        ActivityLoggerService::log(
            description: 'User logged in successfully.',
            school: $user?->school,
            user: $user,
            logName: 'auth',
            properties: array_filter([
                'action' => 'LOGIN',
                'success' => true,
                'guard' => $event->guard,
                'remember' => $event->remember ?? null,
            ], static fn ($value) => $value !== null),
            event: 'auth.login'
        );
    }
}