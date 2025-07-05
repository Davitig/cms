<?php

return [
    App\Providers\Admin\AdminConfigurationProvider::class,
    App\Providers\Admin\ElfinderServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\BladeServiceProvider::class,
    App\Providers\ExceptionHandlerServiceProvider::class,
    App\Providers\GlideServiceProvider::class,
    App\Providers\ViewComposerServiceProvider::class,
    Barryvdh\Debugbar\ServiceProvider::class,
    Cocur\Slugify\Bridge\Laravel\SlugifyServiceProvider::class,
    Mews\Captcha\CaptchaServiceProvider::class,
];
