<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class AdminCmsSettingsTest extends TestCase
{
    public function test_admin_cms_settings_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('cmsSettings.index'));

        $response->assertOk();
    }

    public function test_admin_cms_settings_update()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('cmsSettings.update'), [
            'horizontal_menu' => 1
        ]);

        $response->assertFound();
    }
}
