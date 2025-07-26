<?php

namespace Tests\Feature\Admin\Resources\CmsUser;

use App\Models\CmsUser\CmsUserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminCmsUserPreferenceTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_cms_user_preferences_index()
    {
        $user = $this->createCmsUser();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('cms_users.preferences.index', [$user->id]));

        $response->assertOk();
    }

    public function test_admin_cms_user_preferences_store()
    {
        $user = $this->createCmsUser();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('cms_users.preferences.save', [$user->id]), [
            'horizontal_menu' => 1
        ]);

        $response->assertFound();

        $this->assertTrue((new CmsUserPreference)->cmsUserId($user->id)->exists());
    }

    public function test_admin_cms_user_preferences_update()
    {
        $user = $this->createCmsUser();

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('cms_users.preferences.save', [$user->id]), [
            'horizontal_menu' => 1
        ]);

        $createdPreference = (new CmsUserPreference)->cmsUserId($user->id)
            ->first()
            ?->toArray();

        $this->assertIsArray($createdPreference);

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('cms_users.preferences.save', [$user->id]), [
            'horizontal_menu' => 0
        ]);

        $updatedPreference = (new CmsUserPreference)->cmsUserId($user->id)
            ->first()
            ?->toArray();

        $this->assertIsArray($updatedPreference);

        $this->assertNotEquals($createdPreference, $updatedPreference);
    }
}
