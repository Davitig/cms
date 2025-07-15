<?php

namespace App\View\Composers\Web;

use App\Support\TranslationProvider;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

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

        if (! $current instanceof Model || ! is_object($current)) {
            $trans = &$view->trans;
            $trans ??= new TranslationProvider;

            $current = (object) [
                'title' => $title = ($trans->get('title') ?: request()->getHost()),
                'url_path' => $this->getPath(),
                'meta_title' => $title,
                'meta_desc' => $trans->get('meta_desc') ?: $title
            ];
        } else {
            $current->url_path ??= $this->getPath();

            $current->title ??= request()->getHost();

            $current->meta_title ??= $current->title;
        }
    }

    /**
     * Get the current request path.
     *
     * @return string
     */
    protected function getPath(): string
    {
        return trim(request()->getPathInfo(), '/');
    }
}
