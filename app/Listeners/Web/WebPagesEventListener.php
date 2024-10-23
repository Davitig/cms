<?php

namespace App\Listeners\Web;

use App\Models\Menu;
use App\Models\Page\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Events\Dispatcher;

class WebPagesEventListener
{
    /**
     * Handle view composer events.
     *
     * @param  \Illuminate\Contracts\View\View  $event
     * @return void
     */
    public function onViewComposer(View $event): void
    {
        $menu = (new Menu)->where('main', 1)->first(['id']);

        $pages = [];

        if (! is_null($menu)) {
            $pages = (new Page)->forPublic()
                ->menuId($menu->id)
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
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            'composing: web._partials.pages',
            'App\Listeners\Web\WebPagesEventListener@onViewComposer'
        );
    }
}
