<?php

namespace Tests\Feature\Admin;

use Database\Factories\CmsUserFactory;
use Database\Factories\CmsUserRoleFactory;

class AdminLoginTest extends TestAdmin
{
    public function test_admin_access_needs_authentication(): void
    {
        $response = $this->get(cms_route('dashboard.index'));

        $response->assertRedirect(cms_route('login'));
    }

    public function test_admin_login_view(): void
    {
        $response = $this->get(cms_route('login'));

        $response->assertOk();
    }

    public function test_admin_login_invalid_credentials(): void
    {
        $response = $this->post(cms_route('login.post'), [
            'email' => fake()->email(),
            'password' => fake()->password(8, 8),
        ]);

        $response->assertFound()->assertSessionHasErrors(['email']);
    }

    public function test_admin_login_success(): void
    {
        $cmsUserRole = CmsUserRoleFactory::new()->create();

        $cmsUser = CmsUserFactory::new()
            ->role($cmsUserRole->id)
            ->loginParams($email = fake()->email(), 'password1')
            ->create();

        $response = $this->post(cms_route('login.post'), [
            'email' => $email,
            'password' => 'password1'
        ]);

        $cmsUser->delete();
        $cmsUserRole->delete();

        $response->assertRedirect(cms_route('dashboard.index'));
    }

    public function test_admin_logout(): void
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('logout'));

        $response->assertRedirect(cms_route('login'));
    }
}
