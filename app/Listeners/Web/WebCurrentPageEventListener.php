<?php

namespace App\Listeners\Web;

use App\Models\Eloquent\Model;
use App\Support\TranslationCollection;

class WebCurrentPageEventListener
{
    /**
     * Handle view composer data.
     *
     * @param  \Illuminate\Contracts\View\View  $event
     * @return void
     */
    public function onCurrentPageComposer($event)
    {
        $current = $event->current;

        $trans = app_instance('trans', new TranslationCollection);

        $slug = $this->getPath($event);

        if (! $current instanceof Model) {
            if (is_object($current) && isset($current->title)) {
                $title = $current->title;
            } else {
                $title = ($trans->get('title') ?: $event->app->request->getHost());
            }

            $current = (object) [
                'id' => 0,
                'title' => $title,
                'meta_title' => $title,
                'slug' => $slug,
                'type' => null,
                'image' => asset('assets/images/logo.png'),
                'meta_desc' => $trans->get('meta_desc') ?: $title,
            ];
        } else {
            $current->slug = $slug;
            $current->original_slug = basename($current->slug);

            if (! is_null($current->tab_title)) {
                $current->title .= ' - ' . $current->tab_title;
            }

            if (! is_null($current->tab_slug)) {
                $current->slug .= '/' . $current->tab_slug;
            }

            if (empty($current->meta_desc)) {
                if (! empty($current->description)) {
                    $current->meta_desc = text_limit($current->description);
                } elseif (! empty($current->content)) {
                    $current->meta_desc = text_limit($current->content);
                } else {
                    $current->meta_desc = text_limit($current->title);
                }
            }

            if (empty($current->image)) {
                $current->image = asset('assets/images/logo.png');
            }
        }

        $event->current = $current;
    }

    /**
     * Get the current path without language prefix.
     *
     * @param  \Illuminate\Contracts\View\View  $event
     * @return string
     */
    protected function getPath($event)
    {
        $path = trim($event->app->request->getPathInfo(), '/');

        if (strpos($path, $language = language()) === 0) {
            $path = substr($path, strlen($language) + 1);
        }

        return $path;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen([
            'composing: web.app',
        ],
            'App\Listeners\Web\WebCurrentPageEventListener@onCurrentPageComposer'
        );
    }
}
