<?php

namespace Tests\Feature\Admin;

class AdminFilemanagerTest extends TestAdmin
{
    public function test_admin_filemanager()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('filemanager'));

        $response->assertOk();
    }

    public function test_admin_filemanager_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('filemanager.index'));

        $response->assertOk();
    }

    public function test_admin_filemanager_popup()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('filemanager.popup', [1]));

        $response->assertOk();
    }

    public function test_admin_filemanager_tinymce4()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('filemanager.tinymce5'));

        $response->assertOk();
    }
}
