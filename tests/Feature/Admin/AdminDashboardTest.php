<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminDashboardTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_dashboard()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('dashboard.index'));

        $response->assertOk();
    }
}
