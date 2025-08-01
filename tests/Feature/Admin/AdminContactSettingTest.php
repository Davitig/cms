<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminContactSettingTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_contact_settings_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('settings.contact.index'));

        $response->assertOk();
    }

    public function test_admin_contact_settings_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('settings.contact.save'), [
            'email' => fake()->safeEmail()
        ]);

        $response->assertFound();
    }
}
