<?php

namespace Tests\Feature\Admin\Resources\CmsUser;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminCmsUserPreferenceTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_cms_user_preferences_index()
    {
        $response = $this->actingAs(
            $user = $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('cmsUsers.preferences.index', [$user->id]));

        $response->assertOk();
    }

    public function test_admin_cms_user_preferences_update()
    {
        $response = $this->actingAs(
            $user = $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('cmsUsers.preferences.update', [$user->id]), [
            'horizontal_menu' => 1
        ]);

        $response->assertFound();
    }
}
