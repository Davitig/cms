<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminLoginTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_access_needs_authentication(): void
    {
        $response = $this->get($this->cmsRoute('dashboard.index'));

        $response->assertRedirect($this->cmsRoute('login'));
    }

    public function test_admin_login_view(): void
    {
        $response = $this->get($this->cmsRoute('login'));

        $response->assertOk();
    }

    public function test_admin_login_invalid_credentials(): void
    {
        $response = $this->post($this->cmsRoute('login.post'), [
            'email' => fake()->email(),
            'password' => fake()->password(8, 8),
        ]);

        $response->assertFound()->assertSessionHasErrors(['email']);
    }

    public function test_admin_login_fail_on_suspended_user(): void
    {
        $email = fake()->email();

        $this->createCmsUser(false, function ($factory) use ($email) {
            return $factory->loginParams($email, 'password1')
                ->suspended(true);
        });

        $response = $this->post($this->cmsRoute('login.post'), [
            'email' => $email,
            'password' => 'password1'
        ]);

        $response->assertRedirect($this->cmsRoute('login'))
            ->assertSessionHas('alert.result', false);
    }

    public function test_admin_login_success(): void
    {
        $email = fake()->email();

        $this->createCmsUser(false, function ($factory) use ($email) {
            return $factory->loginParams($email, 'password1');
        });

        $response = $this->post($this->cmsRoute('login.post'), [
            'email' => $email,
            'password' => 'password1'
        ]);

        $response->assertRedirect($this->cmsRoute('dashboard.index'));
    }

    public function test_admin_logout(): void
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('logout'));

        $response->assertRedirect($this->cmsRoute('login'));
    }
}
