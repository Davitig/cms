<?php

namespace App\View\Composers\Web;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class WebSettingsComposer
{
    /**
     * The instance of the settings.
     *
     * @var \Illuminate\Support\Collection
     */
    protected Collection $settings;

    /**
     * Create a new view composer instance.
     */
    public function __construct()
    {
        $this->settings = $this->getSettings();
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('settings', $this->settings);
    }

    /**
     * Get the settings.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getSettings(): Collection
    {
        return new Collection(app('db')->table('web_settings')->first());
    }
}
