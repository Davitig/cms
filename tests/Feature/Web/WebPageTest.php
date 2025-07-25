<?php

namespace Tests\Feature\Web;

use Database\Factories\MenuFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Request;
use Tests\Feature\InteractsWithDynamicPage;
use Tests\TestCase;

class WebPageTest extends TestCase
{
    use RefreshDatabase, InteractsWithDynamicPage;

    public function test_page()
    {
        [$menu, $page] = $this->createPages(null, fn ($factory) => $factory->type('page'));

        $route = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($this->webUrl($page->slug));

        $this->assertSame([
            'controller' => 'WebPageController', 'method' => 'index'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_search_page()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('search')
        );

        $route = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($this->webUrl($page->slug));

        $this->assertSame([
            'controller' => 'WebSearchController', 'method' => 'index'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_page_custom_request_method()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('page')
        );

        $this->app['config']->set('cms.type_request_methods', [
            Request::METHOD_POST => ['page@index' => 'testPostMethod']
        ]);

        $route = $this->getDynamicPageRouteActions($page->slug, Request::METHOD_POST);

        $this->assertSame([
            'controller' => 'WebPageController', 'method' => 'testPostMethod'
        ], $this->getActionsFromRoute($route));
    }

    public function test_page_tabs()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('page')
        );

        $this->app['config']->set('cms.tabs.GET.page@index', [
            'test-uri' => 'testTabMethod'
        ]);

        $route = $this->getDynamicPageRouteActions($page->slug . '/test-uri');

        $this->assertSame([
            'controller' => 'WebPageController', 'method' => 'testTabMethod'
        ], $this->getActionsFromRoute($route));
    }

    public function test_page_tabs_with_parameter()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('page')
        );

        $this->app['config']->set('cms.tabs.POST.page@index', [
            'test-uri/{id}' => 'testTabPostMethodWithParameter'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/test-uri/' . rand(5, 10), Request::METHOD_POST
        );

        $this->assertSame([
            'controller' => 'WebPageController',
            'method' => 'testTabPostMethodWithParameter'
        ], $this->getActionsFromRoute($route));
    }

    public function test_sub_pages()
    {
        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages($menu->id);

        $path = implode('/', array_map(fn ($page) => $page->slug, $pages));

        $this->get($this->webUrl($path))->assertOk();
    }
}
