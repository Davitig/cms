<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminWebSettingTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_web_settings_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('web_settings.index'));

        $response->assertOk();
    }

    public function test_admin_web_settings_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('web_settings.update'), [
            'email' => fake()->safeEmail()
        ]);

        $response->assertFound();
    }
}
