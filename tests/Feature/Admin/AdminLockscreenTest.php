<?php

namespace Tests\Feature\Admin;

use App\Models\CmsUser;
use Tests\TestCase;

class AdminLockscreenTest extends TestCase
{
    protected function getUser(): CmsUser
    {
        return (new CmsUser)->firstOrFail();
    }

    public function test_admin_lockscreen_is_inactive()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('lockscreen'));

        $response->assertFound();
    }

    public function test_admin_lockscreen_activate()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('lockscreen.post'));

        $response->assertRedirect(cms_route('dashboard'))->assertSessionHas('lockscreen', 1);
    }

    public function test_admin_lockscreen_is_active()
    {
        $response = $this->actingAs($this->getUser())->withSession([
            'lockscreen' => 1,
        ])->get(cms_route('dashboard'));

        $response->assertRedirect(cms_route('lockscreen'));
    }

    public function test_admin_lockscreen_invalid_unlocking()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('lockscreen.put'), [
            'password' => str()->random(10),
        ]);

        $response->assertFound()->assertSessionHasErrors();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_lockscreen_successful_unlocking()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('lockscreen.put'), [
            'password' => '123456',
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }
}