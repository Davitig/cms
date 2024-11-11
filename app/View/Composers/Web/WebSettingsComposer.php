<?php

namespace App\View\Composers\Web;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\View\View;

class WebSettingsComposer
{
    /**
     * The Collection instance of the settings.
     *
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected ?Collection $settings = null;

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('settings', $this->getSettings());
    }

    /**
     * Get the settings.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getSettings(): Collection
    {
        if (is_null($this->settings)) {
            $this->settings = new Collection(app('db')->table('web_settings')->first());
        }

        return $this->settings;
    }
}
