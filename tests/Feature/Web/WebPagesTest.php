<?php

namespace Tests\Feature\Web;

use Database\Factories\MenuFactory;
use Tests\Feature\DynamicRoutesTrait;
use Tests\TestCase;

class WebPagesTest extends TestCase
{
    use DynamicRoutesTrait;

    public function test_page_type()
    {
        [$menu, $page] = $this->createPages(null, fn ($factory) => $factory->type('page'));

        $data = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();

        $this->assertSame($data, ['controller' => 'WebPageController', 'method' => 'index']);

        $response->assertOk();
    }

    public function test_page_feedback_type()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('feedback')
        );

        $data = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();

        $this->assertSame($data, [
            'controller' => 'WebFeedbackController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_page_search_type()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('search')
        );

        $data = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();

        $this->assertSame($data, [
            'controller' => 'WebSearchController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_sub_pages()
    {
        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages($menu->id);

        $path = implode('/', array_map(fn ($page) => $page->slug, $pages));

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();

        $response->assertOk();
    }
}
