<?php

namespace App\Listeners\Web;

use Models\Menu;
use Models\Page;

class WebPagesEventListener
{
    /**
     * Handle view composer events.
     *
     * @param  \Illuminate\Contracts\View\View  $event
     * @return void
     */
    public function onViewComposer($event)
    {
        $menu = (new Menu)->where('main', 1)->first(['id']);

        $pages = [];

        if (! is_null($menu)) {
            $pages = (new Page)->forPublic()
                ->where('menu_id', $menu->id)
                ->positionAsc()
                ->get();
        }

        $event->pageItems = make_model_sub_items($pages);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            'composing: web._partials.pages',
            'App\Listeners\Web\WebPagesEventListener@onViewComposer'
        );
    }
}
