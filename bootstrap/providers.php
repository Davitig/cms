<?php

return [
    // LanguageServiceProvider must be on top of other packages.
    App\Providers\LanguageServiceProvider::class,

    // Package Service Providers...
    Cocur\Slugify\Bridge\Laravel\SlugifyServiceProvider::class,

    // Application Service Providers...
    App\Providers\AppServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\ExceptionHandlerServiceProvider::class,
    App\Providers\RouteServiceProvider::class,

    App\Providers\GlideServiceProvider::class,
    // Admin Service Providers
    App\Providers\Admin\ElfinderServiceProvider::class,
    // Web Service Providers
    App\Providers\Web\DynamicRouteServiceProvider::class,
];
