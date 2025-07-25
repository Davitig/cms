<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminSitemapXmlTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_sitemap_xml_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('sitemap.xml.store'));

        $response->assertFound();
    }

    public function test_admin_sitemap_xml_file_created()
    {
        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('sitemap.xml.store'));

        $this->assertFileExists(public_path('sitemap.xml'));
    }
}
