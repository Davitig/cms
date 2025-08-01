<?php

namespace App\View\Composers\Web;

use App\Models\Setting\ContactSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class ContactSettingComposer
{
    /**
     * The collection instance of the settings.
     *
     * @var \Illuminate\Support\Collection
     */
    protected Collection $items;

    /**
     * Create a new view composer instance.
     */
    public function __construct()
    {
        $this->items = $this->getContactSettings();
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('contactSettings', $this->items);
    }

    /**
     * Get the contact settings.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getContactSettings(): Collection
    {
        return (new ContactSetting)->getSettings();
    }
}
