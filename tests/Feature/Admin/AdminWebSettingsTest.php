<?php

namespace Tests\Feature\Admin;

class AdminWebSettingsTest extends TestAdmin
{
    public function test_admin_web_settings_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('webSettings.index'));

        $response->assertOk();
    }

    public function test_admin_web_settings_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('webSettings.update'), [
            'email' => fake()->safeEmail()
        ]);

        $response->assertFound();
    }
}
