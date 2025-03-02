<?php

namespace Tests\Feature\Admin;

class AdminSitemapXmlTest extends TestAdmin
{
    public function test_admin_sitemap_xml_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('sitemap.xml.store'));

        $response->assertFound();
    }
}
