<?php

namespace Tests\Feature\Web;

use Database\Factories\CollectionFactory;
use Database\Factories\Event\EventFactory;
use Database\Factories\Event\EventLanguageFactory;
use Database\Factories\MenuFactory;
use Symfony\Component\HttpFoundation\Request;
use Tests\Feature\DynamicRoutesTrait;
use Tests\TestCase;

class WebEventTest extends TestCase
{
    use DynamicRoutesTrait;

    /**
     * Create a new event.
     *
     * @return array
     */
    protected function createEvent(): array
    {
        $collection = CollectionFactory::new()->eventType()->create();
        $event = EventFactory::new()->has(
            EventLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])),
            'languages'
        )->collectionId($collection->id)->create();

        return [$collection, $event];
    }

    public function test_event_index()
    {
        $collection = CollectionFactory::new()->eventType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebEventController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_event_index_custom_request_method()
    {
        $collection = CollectionFactory::new()->eventType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $this->app['config']->set('cms.type_request_methods', [
            Request::METHOD_POST => ['events@index' => 'testPostMethod']
        ]);

        $route = $this->getDynamicPageRouteActions($page->slug, Request::METHOD_POST);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebEventController', 'method' => 'testPostMethod'
        ]);

        $response->assertOk();
    }

    public function test_event_index_tabs()
    {
        $collection = CollectionFactory::new()->eventType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.GET.events@index', [
            'test-uri' => 'testTabMethod'
        ]);

        $route = $this->getDynamicPageRouteActions($page->slug . '/test-uri');

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebEventController', 'method' => 'testTabMethod'
        ]);
    }

    public function test_event_index_tabs_with_parameter()
    {
        $collection = CollectionFactory::new()->eventType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.POST.events@index', [
            'test-uri/{id}' => 'testTabPostMethodWithParameter'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/test-uri/' . rand(5, 10), Request::METHOD_POST
        );

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebEventController',
            'method' => 'testTabPostMethodWithParameter'
        ]);
    }

    public function test_event_index_sub_pages()
    {
        $collection = CollectionFactory::new()->eventType()->create();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebEventController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_event_show()
    {
        [$collection, $event] = $this->createEvent();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions($path = $page->slug . '/' . $event->slug);

        $response = $this->get($path);

        $page->delete();
        $menu->delete();
        $event->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebEventController', 'method' => 'show'
        ]);

        $response->assertOk();
    }

    public function test_event_show_custom_request_method()
    {
        [$collection, $event] = $this->createEvent();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $this->app['config']->set('cms.type_request_methods', [
            Request::METHOD_PUT => ['events@show' => 'testPutMethod']
        ]);

        $route = $this->getDynamicPageRouteActions(
            $path = $page->slug . '/' . $event->slug, Request::METHOD_PUT
        );

        $response = $this->get($path);

        $page->delete();
        $menu->delete();
        $event->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebEventController', 'method' => 'testPutMethod'
        ]);

        $response->assertOk();
    }

    public function test_event_show_tabs()
    {
        [$collection, $event] = $this->createEvent();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.PUT.events@show', [
            'test-uri' => 'testTabPutMethod'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/' . $event->slug . '/test-uri', Request::METHOD_PUT
        );

        $page->delete();
        $menu->delete();
        $event->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebEventController', 'method' => 'testTabPutMethod'
        ]);
    }

    public function test_event_show_tabs_with_parameter()
    {
        [$collection, $event] = $this->createEvent();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.DELETE.events@show', [
            'test-uri/{id}' => 'testTabDeleteMethodWithParameter'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/' . $event->slug . '/test-uri/' . rand(5, 10),
            Request::METHOD_DELETE
        );

        $page->delete();
        $menu->delete();
        $event->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebEventController',
            'method' => 'testTabDeleteMethodWithParameter'
        ]);
    }

    public function test_event_show_sub_pages()
    {
        [$collection, $event] = $this->createEvent();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
                . '/' . $event->slug
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $event->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebEventController', 'method' => 'show'
        ]);

        $response->assertOk();
    }
}
