<?php

use App\Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        App\Providers\LanguageServiceProvider::class,
        App\Providers\RouteServiceProvider::class
    ])
    ->withRouting(
        commands: __DIR__.'/../routes/console.php'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // cms
            'cms.auth' => \App\Http\Middleware\Admin\AdminAuthenticate::class,
            'cms.guest' => \App\Http\Middleware\Admin\AdminRedirectIfAuthenticated::class,
            'cms.withFullAccess' => \App\Http\Middleware\Admin\AdminCmsUserWithFullAccess::class,
            'cms.lockscreen' => \App\Http\Middleware\Admin\AdminLockscreen::class,
            // web
            'web.maintenance' => \App\Http\Middleware\Web\WebCheckForMaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
