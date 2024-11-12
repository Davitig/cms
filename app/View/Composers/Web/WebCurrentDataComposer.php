<?php

namespace App\View\Composers\Web;

use App\Models\Base\Model;
use App\Support\TranslationCollection;
use Illuminate\Contracts\View\View;

class WebCurrentDataComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $this->handleCurrentData($view);
    }

    /**
     * Handle current data.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function handleCurrentData(View $view): void
    {
        $current = &$view->current;

        $slug = $this->getPath();

        if (! $current instanceof Model) {
            $trans = &$view->trans;
            $trans ??= new TranslationCollection();

            if (is_object($current) && isset($current->title)) {
                $title = $current->title;
            } else {
                $title = ($trans->get('title') ?: request()->getHost());
            }

            $current = (object) [
                'title' => $title,
                'meta_title' => $title,
                'slug' => $slug,
                'image' => null,
                'meta_desc' => $trans->get('meta_desc') ?: $title,
            ];
        } else {
            $current->slug ??= $slug;

            $current->meta_title ??= $current->title;
        }
    }

    /**
     * Get the current path without language prefix.
     *
     * @return string
     */
    protected function getPath(): string
    {
        $path = trim(request()->getPathInfo(), '/');

        if (str_starts_with($path, $language = language())) {
            $path = substr($path, strlen($language) + 1);
        }

        return $path;
    }
}
