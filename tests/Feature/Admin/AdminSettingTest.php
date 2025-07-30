<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminSettingTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_settings_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('settings.index'));

        $response->assertOk();
    }
}
