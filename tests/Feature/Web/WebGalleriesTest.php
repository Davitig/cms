<?php

namespace Tests\Feature\Web;

use Database\Factories\CollectionFactory;
use Database\Factories\GalleryFactory;
use Database\Factories\GalleryLanguageFactory;
use Database\Factories\MenuFactory;
use Symfony\Component\HttpFoundation\Request;
use Tests\Feature\DynamicRoutesTrait;
use Tests\TestCase;

class WebGalleriesTest extends TestCase
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

    public function test_galleries_index()
    {
        $collection = CollectionFactory::new()->galleryType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebGalleriesController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_galleries_index_custom_request_method()
    {
        $collection = CollectionFactory::new()->galleryType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $this->app['config']->set('cms.type_request_methods', [
            Request::METHOD_POST => ['galleries@index' => 'testPostMethod']
        ]);

        $route = $this->getDynamicPageRouteActions($page->slug, Request::METHOD_POST);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebGalleriesController', 'method' => 'testPostMethod'
        ]);

        $response->assertOk();
    }

    public function test_galleries_index_tabs()
    {
        $collection = CollectionFactory::new()->galleryType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.GET.galleries@index', [
            'test-uri' => 'testTabMethod'
        ]);

        $route = $this->getDynamicPageRouteActions($page->slug . '/test-uri');

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebGalleriesController', 'method' => 'testTabMethod'
        ]);
    }

    public function test_galleries_index_tabs_with_parameter()
    {
        $collection = CollectionFactory::new()->galleryType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.PUT.galleries@index', [
            'test-uri/{id}' => 'testTabPutMethodWithParameter'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/test-uri/' . rand(5, 10), Request::METHOD_PUT
        );

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebGalleriesController',
            'method' => 'testTabPutMethodWithParameter'
        ]);
    }

    public function test_galleries_index_sub_pages()
    {
        $collection = CollectionFactory::new()->galleryType()->create();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $collection->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebGalleriesController', 'method' => 'index'
        ]);

        $response->assertOk();
    }
}
