<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(
            prepend: [
                \App\Http\Middleware\SetLocale::class,
            ],
            append: [
                // IdentifyTenant is safe - it only sets context if school param exists
                \App\Http\Middleware\IdentifyTenant::class,
                // NOTE: SetNetwork removed from global - it was causing 404 on routes without {network}
                // Use 'setNetwork' middleware alias on specific route groups instead
            ]
        );

        // API middleware for tenant identification (optional, via headers/body)
        $middleware->api(prepend: [
            \App\Http\Middleware\IdentifyTenantApi::class,
        ]);

        $middleware->alias([
            'verify.tenant' => \App\Http\Middleware\VerifyTenantAccess::class,
            'verify.tenant.access' => \App\Http\Middleware\VerifyTenantAccess::class,
            'setlocale' => \App\Http\Middleware\SetLocale::class,
            'setNetwork' => \App\Http\Middleware\SetNetwork::class,
            'setBranch' => \App\Http\Middleware\SetBranch::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'mainadmin' => \App\Http\Middleware\EnsureMainAdmin::class,
            'superadmin' => \App\Http\Middleware\EnsureIsSuperAdmin::class,
            'active' => \App\Http\Middleware\CheckUserActive::class,
            'match.school.network' => \App\Http\Middleware\EnsureSchoolNetworkMatch::class,
            'ensure.school.network.match' => \App\Http\Middleware\EnsureSchoolNetworkMatch::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

