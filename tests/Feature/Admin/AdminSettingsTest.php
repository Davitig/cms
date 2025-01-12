<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    public function test_admin_settings_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('settings.index'));

        $response->assertOk();
    }

    public function test_admin_settings_update()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('settings.update'), [
            'horizontal_menu' => 1
        ]);

        $response->assertFound();
    }
}
