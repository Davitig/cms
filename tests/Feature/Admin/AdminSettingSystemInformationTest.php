<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminSettingSystemInformationTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_settings_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('settings.system.index'));

        $response->assertOk();
    }
}
