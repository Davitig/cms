<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        App\Providers\EloquentServiceProvider::class,
        App\Providers\LanguageServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ])
    ->registered(function (Application $app) {
        $app->register(App\Providers\Web\WebDynamicRouteServiceProvider::class);
    })
    ->withRouting(
        commands: __DIR__.'/../routes/console.php'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // cms
            'cms.lang' => \App\Http\Middleware\Admin\AdminLanguageValidate::class,
            'cms.auth' => \App\Http\Middleware\Admin\AdminAuthenticate::class,
            'cms.guest' => \App\Http\Middleware\Admin\AdminRedirectIfAuthenticated::class,
            'cms.fullAccess' => \App\Http\Middleware\Admin\AdminCmsUserWithFullAccess::class,
            // web
            'web.lang' => \App\Http\Middleware\Web\WebLanguageValidate::class,
            'web.dynamicRoute' => \App\Http\Middleware\Web\WebHandleDynamicRoute::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
