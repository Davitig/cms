<?php

namespace Tests\Feature\Admin;

class AdminLockscreenTest extends TestAdmin
{
    public function test_admin_lockscreen_is_unlocked()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('lockscreen'));

        $response->assertFound();
    }

    public function test_admin_lockscreen_lock()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('lockscreen.lock'));

        $response->assertRedirect(cms_route('dashboard'))->assertSessionHas('lockscreen', 1);
    }

    public function test_admin_lockscreen_is_locked()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->withSession([
            'lockscreen' => 1,
        ])->get(cms_route('dashboard'));

        $response->assertRedirect(cms_route('lockscreen'));
    }

    public function test_admin_lockscreen_invalid_unlocking()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('lockscreen.unlock'), [
            'password' => str()->random(10),
        ]);

        $response->assertFound()->assertSessionHasErrors();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_lockscreen_successful_unlocking()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('lockscreen.unlock'), [
            'password' => 'password',
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }
}
