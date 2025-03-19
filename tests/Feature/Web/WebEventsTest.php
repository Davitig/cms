<?php

namespace Tests\Feature\Web;

use Database\Factories\CollectionFactory;
use Database\Factories\Event\EventFactory;
use Database\Factories\Event\EventLanguageFactory;
use Database\Factories\MenuFactory;
use Tests\Feature\DynamicRoutesTrait;
use Tests\TestCase;

class WebEventsTest extends TestCase
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

        $data = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebEventsController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_sub_pages_event_index()
    {
        $collection = CollectionFactory::new()->eventType()->create();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebEventsController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_event_show()
    {
        [$collection, $event] = $this->createEvent();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions($path = $page->slug . '/' . $event->slug);

        $response = $this->get($path);

        $page->delete();
        $menu->delete();
        $event->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebEventsController', 'method' => 'show'
        ]);

        $response->assertOk();
    }

    public function test_sub_pages_event_show()
    {
        [$collection, $event] = $this->createEvent();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('events')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
            . '/' . $event->slug
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $event->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebEventsController', 'method' => 'show'
        ]);

        $response->assertOk();
    }
}
