<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    // ...
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\IdentifyTenant::class,
        ]);

        $middleware->alias([
            'verify.tenant' => \App\Http\Middleware\VerifyTenantAccess::class,
            'setlocale' => \App\Http\Middleware\SetLocale::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'superadmin' => \App\Http\Middleware\EnsureIsSuperAdmin::class,
            'active' => \App\Http\Middleware\CheckUserActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

