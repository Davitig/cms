<?php

namespace Tests\Feature\Admin;

class AdminFilemanagerTest extends TestAdmin
{
    public function test_admin_filemanager()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('fileManager'));

        $response->assertOk();
    }

    public function test_admin_filemanager_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('fileManager.index'));

        $response->assertOk();
    }

    public function test_admin_filemanager_popup()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('fileManager.popup', [1]));

        $response->assertOk();
    }

    public function test_admin_filemanager_tinymce4()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('fileManager.tinymce5'));

        $response->assertOk();
    }
}
