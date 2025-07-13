<?php

namespace Tests\Feature\Web;

use Database\Factories\CollectionFactory;
use Database\Factories\Event\EventFactory;
use Database\Factories\Event\EventLanguageFactory;
use Database\Factories\MenuFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Request;
use Tests\Feature\CreatesLanguageService;
use Tests\Feature\InteractsWithDynamicPage;
use Tests\TestCase;

class WebEventTest extends TestCase
{
    use RefreshDatabase, CreatesLanguageService, InteractsWithDynamicPage;

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

        $this->assertSame([
            'controller' => 'WebEventController', 'method' => 'index'
        ], $this->getActionsFromRoute($route));

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

        $this->assertSame([
            'controller' => 'WebEventController', 'method' => 'testPostMethod'
        ], $this->getActionsFromRoute($route));

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

        $this->assertSame([
            'controller' => 'WebEventController', 'method' => 'testTabMethod'
        ], $this->getActionsFromRoute($route));
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

        $this->assertSame([
            'controller' => 'WebEventController',
            'method' => 'testTabPostMethodWithParameter'
        ], $this->getActionsFromRoute($route));
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

        $this->assertSame([
            'controller' => 'WebEventController', 'method' => 'index'
        ], $this->getActionsFromRoute($route));

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

        $this->assertSame([
            'controller' => 'WebEventController', 'method' => 'show'
        ], $this->getActionsFromRoute($route));

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

        $this->assertSame([
            'controller' => 'WebEventController', 'method' => 'testPutMethod'
        ], $this->getActionsFromRoute($route));

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

        $this->assertSame([
            'controller' => 'WebEventController', 'method' => 'testTabPutMethod'
        ], $this->getActionsFromRoute($route));
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

        $this->assertSame([
            'controller' => 'WebEventController',
            'method' => 'testTabDeleteMethodWithParameter'
        ], $this->getActionsFromRoute($route));
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

        $this->assertSame([
            'controller' => 'WebEventController', 'method' => 'show'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }
}
