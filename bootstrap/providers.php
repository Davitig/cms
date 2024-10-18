<?php

return [
    // Package Service Providers...
    Cocur\Slugify\Bridge\Laravel\SlugifyServiceProvider::class,

    // Application Service Providers...
    App\Providers\AppServiceProvider::class,
    // LanguageServiceProvider must be on top of others.
    App\Providers\LanguageServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\ExceptionHandlerServiceProvider::class,

    App\Providers\GlideServiceProvider::class,
    // Admin Service Providers
    App\Providers\Admin\ElfinderServiceProvider::class,
    // Web Service Providers
    App\Providers\Web\DynamicRouteServiceProvider::class,
];
