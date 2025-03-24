<?php

return [
    App\Providers\Admin\ElfinderServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\EloquentServiceProvider::class,
    App\Providers\ExceptionHandlerServiceProvider::class,
    App\Providers\GlideServiceProvider::class,
    App\Providers\ViewComposerServiceProvider::class,
    Cocur\Slugify\Bridge\Laravel\SlugifyServiceProvider::class,
    Mews\Captcha\CaptchaServiceProvider::class,
];
