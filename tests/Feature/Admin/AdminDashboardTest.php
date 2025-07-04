<?php

namespace Tests\Feature\Admin;

class AdminDashboardTest extends TestAdmin
{
    public function test_admin_dashboard()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('dashboard.index'));

        $response->assertOk();
    }
}
