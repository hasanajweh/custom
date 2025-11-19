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
            \App\Http\Middleware\SetNetwork::class,
        ]);

        $middleware->alias([
            'verify.tenant' => \App\Http\Middleware\VerifyTenantAccess::class,
            'setlocale' => \App\Http\Middleware\SetLocale::class,
            'setNetwork' => \App\Http\Middleware\SetNetwork::class,
            'setBranch' => \App\Http\Middleware\SetBranch::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'mainadmin' => \App\Http\Middleware\EnsureMainAdmin::class,
            'superadmin' => \App\Http\Middleware\EnsureIsSuperAdmin::class,
            'active' => \App\Http\Middleware\CheckUserActive::class,
            'match.school.network' => \App\Http\Middleware\EnsureSchoolNetworkMatch::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

