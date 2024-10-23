<?php

namespace App\Listeners\Web;

use App\Models\Base\Model;
use Illuminate\Contracts\View\View;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;

class WebBreadcrumbEventListener
{
    /**
     * Handle view composer data.
     *
     * @param  \Illuminate\Contracts\View\View  $event
     * @return void
     */
    public function onBreadcrumbComposer(View $event): void
    {
        if ($event->current instanceof Model) {
            $breadcrumb = app_instance('breadcrumb');

            if ($breadcrumb instanceof Collection) {
                if (! ($parent = $breadcrumb->last()) instanceof $event->current) {
                    $event->current->slug = $parent->slug . '/' . $event->current->slug;

                    $breadcrumb->push($event->current);

                    if (! is_null($event->current->tab_slug)
                        && ! is_null($event->current->tab_title)
                    ) {
                        $current = $event->current->newInstance();

                        $current->slug = $event->current->tab_slug;

                        $current->title = $event->current->tab_title;

                        $breadcrumb->push($current);
                    }
                }
            } else {
                app()->instance('breadcrumb', new Collection([$event->current]));
            }
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
            'composing: web._partials.breadcrumb',
        ],
            'App\Listeners\Web\WebBreadcrumbEventListener@onBreadcrumbComposer'
        );
    }
}
