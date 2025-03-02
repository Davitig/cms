<?php

namespace Tests\Feature\Admin;

class AdminDashboardTest extends TestAdmin
{
    public function test_admin_dashboard()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('dashboard'));

        $response->assertOk();
    }
}
