<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_admin_dashboard()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('dashboard'));

        $response->assertOk();
    }
}
