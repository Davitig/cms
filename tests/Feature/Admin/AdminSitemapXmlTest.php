<?php

namespace Tests\Feature\Admin;

class AdminSitemapXmlTest extends TestAdmin
{
    public function test_admin_sitemap_xml_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('sitemap.xml.store'));

        $response->assertFound();
    }

    public function test_admin_sitemap_xml_file_created()
    {
        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('sitemap.xml.store'));

        $this->assertFileExists(public_path('sitemap.xml'));
    }
}
