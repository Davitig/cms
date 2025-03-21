<?php

namespace Tests\Feature\Web;

use Database\Factories\CollectionFactory;
use Database\Factories\GalleryFactory;
use Database\Factories\GalleryLanguageFactory;
use Database\Factories\MenuFactory;
use Symfony\Component\HttpFoundation\Request;
use Tests\Feature\DynamicRoutesTrait;
use Tests\TestCase;

class WebVideosTest extends TestCase
{
    use DynamicRoutesTrait;

    /**
     * Create a new gallery.
     *
     * @param  string|null  $type
     * @return array
     */
    protected function createGallery(?string $type = null): array
    {
        $collection = CollectionFactory::new()->galleryType()->create();
        $gallery = GalleryFactory::new()->has(
            GalleryLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])),
            'languages'
        )->collectionId($collection->id)
            ->when($type, fn ($factory) => $factory->type($type))
            ->create();

        return [$collection, $gallery];
    }

    public function test_videos_show()
    {
        [$collection, $gallery] = $this->createGallery('videos');

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions($path = $page->slug . '/' . $gallery->slug);

        $response = $this->get($path);

        $page->delete();
        $menu->delete();
        $gallery->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebVideosController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_videos_show_custom_request_method()
    {
        [$collection, $gallery] = $this->createGallery('videos');

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $this->app['config']->set('cms.type_request_methods', [
            Request::METHOD_PUT => ['videos@index' => 'testPutMethod']
        ]);

        $route = $this->getDynamicPageRouteActions(
            $path = $page->slug . '/' . $gallery->slug, Request::METHOD_PUT
        );

        $response = $this->get($path);

        $page->delete();
        $menu->delete();
        $gallery->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebVideosController', 'method' => 'testPutMethod'
        ]);

        $response->assertOk();
    }

    public function test_videos_show_tabs_with_parameter()
    {
        [$collection, $event] = $this->createGallery('videos');

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.POST.videos@index', [
            'test-uri/{id}' => 'testTabPostMethodWithParameter'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/' . $event->slug . '/test-uri/' . rand(5, 10),
            Request::METHOD_POST
        );

        $page->delete();
        $menu->delete();
        $event->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebVideosController',
            'method' => 'testTabPostMethodWithParameter'
        ]);
    }

    public function test_videos_show_sub_pages()
    {
        [$collection, $gallery] = $this->createGallery('videos');

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
                . '/' . $gallery->slug
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $gallery->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebVideosController', 'method' => 'index'
        ]);

        $response->assertOk();
    }
}
