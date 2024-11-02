<?php

return [
    App\Providers\Admin\ElfinderServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\EloquentUserServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\ExceptionHandlerServiceProvider::class,
    App\Providers\GlideServiceProvider::class,
    App\Providers\LanguageServiceProvider::class,
    App\Providers\Web\DynamicRouteServiceProvider::class,
    Cocur\Slugify\Bridge\Laravel\SlugifyServiceProvider::class,
];
