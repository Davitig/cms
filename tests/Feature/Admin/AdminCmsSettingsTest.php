<?php

namespace Tests\Feature\Admin;

class AdminCmsSettingsTest extends TestAdmin
{
    public function test_admin_cms_settings_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('cmsSettings.index'));

        $response->assertOk();
    }

    public function test_admin_cms_settings_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('cmsSettings.update'), [
            'horizontal_menu' => 1
        ]);

        $response->assertFound();
    }
}
