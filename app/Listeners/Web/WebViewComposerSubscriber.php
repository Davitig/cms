<?php

namespace App\Listeners\Web;

use App\Models\Base\Model;
use App\Models\Menu;
use App\Models\Page\Page;
use App\Support\TranslationCollection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class WebViewComposerSubscriber
{
    /**
     * Handle current page view composer data.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function handleCurrentPageComposer(View $view): void
    {
        $current = $view->current;

        $trans = app_instance('trans', new TranslationCollection);

        $slug = $this->getPath($view);

        if (! $current instanceof Model) {
            if (is_object($current) && isset($current->title)) {
                $title = $current->title;
            } else {
                $title = ($trans->get('title') ?: $view->app->request->getHost());
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
                    $current->meta_desc = str($current->description)->limit();
                } elseif (! empty($current->content)) {
                    $current->meta_desc = str($current->content)->limit();
                } else {
                    $current->meta_desc = str($current->title)->limit();
                }
            }

            if (empty($current->image)) {
                $current->image = asset('assets/images/logo.png');
            }
        }

        $view->current = $current;
    }

    /**
     * Get the current path without language prefix.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return string
     */
    protected function getPath(View $view): string
    {
        $path = trim($view->app->request->getPathInfo(), '/');

        if (str_starts_with($path, $language = language())) {
            $path = substr($path, strlen($language) + 1);
        }

        return $path;
    }

    /**
     * Handle pages view composer data.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function handlePagesComposer(View $view): void
    {
        $menu = (new Menu)->where('main', 1)->first(['id']);

        $pages = [];

        if (! is_null($menu)) {
            $pages = (new Page)->forPublic()
                ->menuId($menu->id)
                ->positionAsc()
                ->get();
        }

        $view->pageItems = make_model_sub_items($pages);
    }

    /**
     * Handle breadcrumb view composer data.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function handleBreadcrumbComposer(View $view): void
    {
        if ($view->current instanceof Model) {
            $breadcrumb = app_instance('breadcrumb');

            if ($breadcrumb instanceof Collection) {
                if (! ($parent = $breadcrumb->last()) instanceof $view->current) {
                    $view->current->slug = $parent->slug . '/' . $view->current->slug;

                    $breadcrumb->push($view->current);

                    if (! is_null($view->current->tab_slug)
                        && ! is_null($view->current->tab_title)
                    ) {
                        $current = $view->current->newInstance();

                        $current->slug = $view->current->tab_slug;

                        $current->title = $view->current->tab_title;

                        $breadcrumb->push($current);
                    }
                }
            } else {
                app()->instance('breadcrumb', new Collection([$view->current]));
            }
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(): array
    {
        return [
            // apply current data to app view and includes
            'composing: web.app' => 'handleCurrentPageComposer',
            'composing: web._partials.pages' => 'handlePagesComposer',
            'composing: web._partials.breadcrumb' => 'handleBreadcrumbComposer',
        ];
    }
}
