<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class AdminWebSettingsTest extends TestCase
{
    public function test_admin_web_settings_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('webSettings.index'));

        $response->assertOk();
    }

    public function test_admin_web_settings_update()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('webSettings.update'), [
            'email' => fake()->safeEmail()
        ]);

        $response->assertFound();
    }
}
