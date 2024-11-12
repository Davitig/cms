<?php

namespace App\View\Composers\Web;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class WebBreadcrumbComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $this->handleBreadcrumb($view);
    }

    /**
     * Handle breadcrumb data.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function handleBreadcrumb(View $view): void
    {
        if ($breadcrumb = $view->breadcrumb) {
            app()->instance(
                'breadcrumb',
                $breadcrumb instanceof Collection ? $breadcrumb : new Collection($breadcrumb)
            );

            return;
        }

        if (! is_object($current = $view->current) || empty($current->slug)) {
            return;
        }

        $breadcrumb = app_make('breadcrumb');

        if (! $breadcrumb instanceof Collection) {
            app()->instance('breadcrumb', new Collection([$current]));
        } elseif (! $breadcrumb->last() instanceof $current) {
            $breadcrumb->push($current);
        }
    }
}
