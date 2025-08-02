<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Uri;
use Tests\Feature\Admin\TestAdmin;
use Tests\Feature\InteractsWithDynamicPage;

class RouteWithoutLanguageProviderTest extends TestAdmin
{
    use RefreshDatabase, InteractsWithDynamicPage;

    protected bool $languageProviderEnabled = false;

    public function test_admin_route_without_language_provider()
    {
        $this->assertTrue(language()->isEmpty());

        $uri = Uri::of($url = $this->cmsRoute('dashboard.index'));

        $this->assertTrue(str_starts_with($uri->path(), cms_path()));

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($url);

        $response->assertOk();
    }

    public function test_web_route_with_language_provider()
    {
        $this->assertTrue(language()->isEmpty());

        [$menu, $page] = $this->createPages(null, fn ($factory) => $factory->type('page'));

        $response = $this->get($this->webUrl($page->slug));

        $response->assertServiceUnavailable();
    }
}
