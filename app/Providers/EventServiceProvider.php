<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
//        Registered::class => [
//            SendEmailVerificationNotification::class,
//        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array<int, class-string|string>
     */
    protected $subscribe = [
        \App\Listeners\Admin\AdminUserPanelEventListener::class,

        \App\Listeners\Web\WebCurrentPageEventListener::class,
        \App\Listeners\Web\WebPagesEventListener::class,
        \App\Listeners\Web\WebBreadcrumbEventListener::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
