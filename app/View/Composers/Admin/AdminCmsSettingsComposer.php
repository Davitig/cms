<?php

namespace App\View\Composers\Admin;

use App\Models\CmsUser;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use stdClass;

class AdminCmsSettingsComposer
{
    /**
     * The CmsUser instance.
     *
     * @var \App\Models\CmsUser|mixed|null
     */
    protected ?CmsUser $user = null;

    /**
     * The instance of the settings.
     *
     * @var \stdClass|null
     */
    protected ?stdClass $settings = null;

    /**
     * Create a new view composer instance.
     *
     * @param  \Illuminate\Auth\AuthManager  $auth
     */
    public function __construct(AuthManager $auth)
    {
        $this->user = $auth->guard('cms')->user();
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('cmsSettings', $this->getSettings());
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

        if (is_null($this->settings)) {
            $this->settings = app('db')->table('cms_settings')
                ->where('cms_user_id', $this->user->id)
                ->first();

            if (! is_null($this->settings)) {
                $this->settings->body = <<< EOT
{$this->settings->sidebar_direction} {$this->settings->layout_boxed} {$this->settings->skin_sidebar}
{$this->settings->skin_user_menu} {$this->settings->skin_horizontal}
EOT;
                $this->settings->body = preg_replace(
                    '/\s+/', ' ', trim($this->settings->body)
                );
            }
        }

        return new Collection($this->settings);
    }
}
