<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Uri;
use Tests\Feature\Admin\TestAdmin;

class RouteWithLanguageProviderSingleItemTest extends TestAdmin
{
    use RefreshDatabase, InteractsWithDynamicPage;

    /**
     * Get the languages of an environment variable.
     *
     * @return array
     */
    public function getEnvLanguages(): array
    {
        return ['en'];
    }

    public function test_web_route_with_language_provider()
    {
        $this->assertTrue(language()->count() === 1);

        [$menu, $page] = $this->createPages(null, fn ($factory) => $factory->type('page'));

        $uri = Uri::of($url = $this->webUrl($page->slug));

        $this->assertTrue(! str_starts_with(
            $uri->path(), language()->active() . '/'
        ));

        $response = $this->get($url);

        $response->assertOk();
    }
}
