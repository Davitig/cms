<?php

namespace Tests\Feature\Admin;

class AdminWebSettingTest extends TestAdmin
{
    public function test_admin_web_settings_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('webSettings.index'));

        $response->assertOk();
    }

    public function test_admin_web_settings_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('webSettings.update'), [
            'email' => fake()->safeEmail()
        ]);

        $response->assertFound();
    }
}
