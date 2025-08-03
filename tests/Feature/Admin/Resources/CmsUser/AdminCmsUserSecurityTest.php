<?php

namespace Tests\Feature\Admin\Resources\CmsUser;

use Database\Factories\CmsUser\CmsUserFactory;
use Database\Factories\CmsUser\CmsUserRoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminCmsUserSecurityTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_cms_user_security_index()
    {
        $response = $this->actingAs(
            $user = $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('cms_users.security', [$user->id]));

        $response->assertOk();
    }

    public function test_admin_cms_user_password_update()
    {
        $user = CmsUserFactory::new()->roleId(
            CmsUserRoleFactory::new()->create()->id
        )->loginParams(fake()->email(), 'password1')->create();

        $response = $this->actingAs(
            $user, 'cms'
        )->put($this->cmsRoute('cms_users.password', [$user->id]), [
            'current_password' => 'password1',
            'password' => 'password0',
            'password_confirmation' => 'password0'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }
}
