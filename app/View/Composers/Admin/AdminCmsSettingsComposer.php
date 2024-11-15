<?php

namespace App\View\Composers\Admin;

use App\Models\CmsUser;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class AdminCmsSettingsComposer
{
    /**
     * The CmsUser instance.
     *
     * @var \App\Models\CmsUser|null
     */
    protected ?CmsUser $user = null;

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
        $this->user = $auth->guard('cms')->user();

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
        if (is_null($this->user)) {
            return new Collection;
        }

        $settings = app('db')->table('cms_settings')
            ->where('cms_user_id', $this->user->id)
            ->first();

        if (! is_null($settings)) {
            $settings->body = <<< EOT
$settings->sidebar_direction $settings->layout_boxed $settings->skin_sidebar
$settings->skin_user_menu $settings->skin_horizontal
EOT;
            $settings->body = preg_replace(
                '/\s+/', ' ', trim($settings->body)
            );
        }

        return new Collection($settings);
    }
}
