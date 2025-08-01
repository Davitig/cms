<?php

namespace App\View\Composers\Admin;

use App\Models\CmsUser\CmsUserPreference;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class AdminCmsUserPreferencesComposer
{
    /**
     * The CmsUser instance.
     *
     * @var string|int|null
     */
    protected string|int|null $cmsUserId = null;

    /**
     * The collection instance of the preferences.
     *
     * @var \Illuminate\Support\Collection
     */
    protected Collection $items;

    /**
     * Create a new view composer instance.
     *
     * @param  \Illuminate\Auth\AuthManager  $auth
     */
    public function __construct(AuthManager $auth)
    {
        $this->cmsUserId = $auth->guard('cms')->id();

        $this->items = $this->getPreferences();
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('preferences', $this->items);
    }

    /**
     * Get the CMS User preferences.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getPreferences(): Collection
    {
        if (is_null($this->cmsUserId)) {
            return new Collection;
        }

        return new Collection(
            (array) (new CmsUserPreference)->cmsUserId($this->cmsUserId)->first()?->toArray()
        );
    }
}
