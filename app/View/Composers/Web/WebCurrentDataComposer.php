<?php

namespace App\View\Composers\Web;

use App\Models\Setting\MetaSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
            $metaSettings = $this->getMetaSetting();

            $current = (object) [
                'site_name' => $metaSettings->get('site_name'),
                'title' => $title = ($metaSettings->get('title') ?: request()->getHost()),
                'meta_title' => $title,
                'meta_desc' => $metaSettings->get('description'),
                'url_path' => $this->getPath(),
                'image' => $metaSettings->get('image')
            ];
        } else {
            $metaSettings = null;

            if (! isset($current->site_name, $current->title, $current->meta_desc)) {
                $metaSettings = $this->getMetaSetting();
            }

            $current->site_name ??= $metaSettings?->get('site_name');

            $current->title ??= $metaSettings?->get('title') ?: request()->getHost();

            $current->meta_desc ??= $metaSettings?->get('description');

            $current->url_path ??= $this->getPath();
        }
    }

    /**
     * Get the meta settings.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getMetaSetting(): Collection
    {
        return (new MetaSetting)->getSettings();
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
