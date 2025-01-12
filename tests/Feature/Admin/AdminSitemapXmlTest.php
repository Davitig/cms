<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class AdminSitemapXmlTest extends TestCase
{
    public function test_admin_sitemap_xml_store()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('sitemap.xml.store'));

        $response->assertFound();
    }
}
