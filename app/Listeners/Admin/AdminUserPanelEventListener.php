<?php

namespace App\Listeners\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Events\Dispatcher;

class AdminUserPanelEventListener
{
    /**
     * Handle view composer data.
     *
     * @param  \Illuminate\Contracts\View\View  $event
     * @return void
     */
    public function onUserPanelComposer(View $event): void
    {
        $event->sitemapXmlTime = null;

        if (file_exists($file = public_path('sitemap.xml'))) {
            $event->sitemapXmlTime = filectime($file);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen([
                'composing: admin._partials.user',
                'composing: admin._partials.horizontal_menu'
            ],
            'App\Listeners\Admin\AdminUserPanelEventListener@onUserPanelComposer'
        );
    }
}
