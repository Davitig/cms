<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminFileManagerTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_file_manager()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('file_manager'));

        $response->assertOk();
    }

    public function test_admin_file_manager_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('file_manager.index'));

        $response->assertOk();
    }

    public function test_admin_file_manager_popup()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('file_manager.popup', [1]));

        $response->assertOk();
    }

    public function test_admin_file_manager_tinymce4()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('file_manager.tinymce5'));

        $response->assertOk();
    }
}
