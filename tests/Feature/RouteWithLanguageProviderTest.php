<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Uri;
use Tests\Feature\Admin\TestAdmin;
use Tests\Feature\InteractsWithDynamicPage;

class RouteWithLanguageProviderTest extends TestAdmin
{
    use RefreshDatabase, InteractsWithDynamicPage;

    public function test_admin_route_with_language_provider()
    {
        $this->assertTrue(! language()->isEmpty());

        $uri = Uri::of($url = $this->cmsRoute('dashboard.index'));

        $this->assertTrue(str_starts_with(
            $uri->path(), cms_path(null, true)
        ));

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($url);

        $response->assertOk();
    }

    public function test_web_route_with_language_provider()
    {
        $this->assertTrue(! language()->isEmpty());

        [$menu, $page] = $this->createPages(null, fn ($factory) => $factory->type('page'));

        $uri = Uri::of($url = $this->webUrl($page->slug));

        $this->assertTrue(str_starts_with(
            $uri->path(), language()->active()
        ));

        $response = $this->get($url);

        $response->assertOk();
    }
}
