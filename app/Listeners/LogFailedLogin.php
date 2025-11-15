<?php

namespace App\Listeners;

use App\Services\ActivityLoggerService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LogFailedLogin
{
    public function __construct(private readonly Request $request)
    {
    }

    public function handle(Failed $event): void
    {
        $user = $event->user;
        $credentials = Arr::except($event->credentials, ['password']);

        ActivityLoggerService::log(
            description: 'Failed login attempt.',
            school: $user?->school,
            user: $user,
            logName: 'auth',
            properties: array_filter([
                'action' => 'LOGIN',
                'success' => false,
                'guard' => $event->guard,
                'user_id' => $user?->id,
                'school_id' => $user?->school_id,
                'credentials' => $credentials,
            ], static fn ($value) => $value !== null && $value !== []),
            event: 'auth.login'
        );
    }
}