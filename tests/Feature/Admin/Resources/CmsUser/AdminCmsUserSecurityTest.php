<?php

namespace Tests\Feature\Admin\Resources\CmsUser;

use Database\Factories\CmsUserFactory;
use Database\Factories\CmsUserRoleFactory;
use Tests\Feature\Admin\TestAdmin;

class AdminCmsUserSecurityTest extends TestAdmin
{
    public function test_admin_cms_user_security_index()
    {
        $response = $this->actingAs(
            $user = $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('cmsUsers.security', [$user->id]));

        $response->assertOk();
    }

    public function test_admin_cms_user_password_update()
    {
        $user = CmsUserFactory::new()->role(
            CmsUserRoleFactory::new()->create()->id
        )->loginParams(fake()->email(), 'password1')->create();

        $response = $this->actingAs(
            $user, 'cms'
        )->put(cms_route('cmsUsers.password', [$user->id]), [
            'current_password' => 'password1',
            'password' => 'password0',
            'password_confirmation' => 'password0'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }
}
