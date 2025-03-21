<?php

namespace Tests\Feature\Web;

use Database\Factories\MenuFactory;
use Symfony\Component\HttpFoundation\Request;
use Tests\Feature\DynamicRoutesTrait;
use Tests\TestCase;

class WebPagesTest extends TestCase
{
    use DynamicRoutesTrait;

    public function test_page_type()
    {
        [$menu, $page] = $this->createPages(null, fn ($factory) => $factory->type('page'));

        $route = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebPageController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_page_feedback_type()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('feedback')
        );

        $route = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebFeedbackController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_page_search_type()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('search')
        );

        $route = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebSearchController', 'method' => 'index'
        ]);

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

        $page->delete();
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebPageController', 'method' => 'testPostMethod'
        ]);
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

        $page->delete();
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebPageController', 'method' => 'testTabMethod'
        ]);
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

        $page->delete();
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebPageController',
            'method' => 'testTabPostMethodWithParameter'
        ]);
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
