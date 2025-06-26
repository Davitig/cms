<?php

namespace App\View\Composers\Admin;

use App\Models\CmsSetting;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class AdminCmsSettingsComposer
{
    /**
     * The CmsUser instance.
     *
     * @var string|int|null
     */
    protected string|int|null $cmsUserId = null;

    /**
     * The instance of the settings.
     *
     * @var \Illuminate\Support\Collection
     */
    protected Collection $settings;

    /**
     * Create a new view composer instance.
     *
     * @param  \Illuminate\Auth\AuthManager  $auth
     */
    public function __construct(AuthManager $auth)
    {
        $this->cmsUserId = $auth->guard('cms')->id();

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
        $view->with('cmsSettings', $this->settings);
    }

    /**
     * Get the CMS settings.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getSettings(): Collection
    {
        if (is_null($this->cmsUserId)) {
            return new Collection;
        }

        return new Collection(
            (new CmsSetting)->cmsUserId($this->cmsUserId)->first()?->toArray()
        );
    }
}
