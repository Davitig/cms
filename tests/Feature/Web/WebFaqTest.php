<?php

namespace Tests\Feature\Web;

use Database\Factories\CollectionFactory;
use Database\Factories\MenuFactory;
use Symfony\Component\HttpFoundation\Request;
use Tests\Feature\DynamicRoutesTrait;
use Tests\TestCase;

class WebFaqTest extends TestCase
{
    use DynamicRoutesTrait;

    public function test_faq_index()
    {
        $collection = CollectionFactory::new()->faqType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('faq')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebFaqController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_faq_index_custom_request_method()
    {
        $collection = CollectionFactory::new()->faqType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('faq')->typeId($collection->id)
        );

        $this->app['config']->set('cms.type_request_methods', [
            Request::METHOD_POST => ['faq@index' => 'testPostMethod']
        ]);

        $route = $this->getDynamicPageRouteActions($page->slug, Request::METHOD_POST);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebFaqController', 'method' => 'testPostMethod'
        ]);

        $response->assertOk();
    }

    public function test_faq_index_tabs()
    {
        $collection = CollectionFactory::new()->faqType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('faq')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.GET.faq@index', [
            'test-uri' => 'testTabMethod'
        ]);

        $route = $this->getDynamicPageRouteActions($page->slug . '/test-uri');

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebFaqController', 'method' => 'testTabMethod'
        ]);
    }

    public function test_faq_index_tabs_with_parameter()
    {
        $collection = CollectionFactory::new()->faqType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('faq')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.POST.faq@index', [
            'test-uri/{id}' => 'testTabPostMethodWithParameter'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/test-uri/' . rand(5, 10), Request::METHOD_POST
        );

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebFaqController',
            'method' => 'testTabPostMethodWithParameter'
        ]);
    }

    public function test_faq_index_sub_pages()
    {
        $collection = CollectionFactory::new()->faqType()->create();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('faq')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebFaqController', 'method' => 'index'
        ]);

        $response->assertOk();
    }
}
