<?php

namespace Tests\Feature\Admin;

class AdminLoginTest extends TestAdmin
{
    public function test_admin_access_needs_authentication(): void
    {
        $response = $this->get(cms_route('dashboard'));

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
            'email' => 'invalid@example.com',
            'password' => str()->random()
        ]);

        $response->assertFound()->assertSessionHasErrors(['email']);
    }

    public function test_admin_login_success(): void
    {
        $response = $this->post(cms_route('login.post'), [
            'email' => 'full-access-test@example.com',
            'password' => 'password'
        ]);

        $response->assertRedirect(cms_route('dashboard'));
    }

    public function test_admin_logout(): void
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('logout'));

        $response->assertRedirect(cms_route('login'));
    }
}
