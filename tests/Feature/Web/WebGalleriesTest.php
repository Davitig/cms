<?php

namespace Tests\Feature\Web;

use Database\Factories\CollectionFactory;
use Database\Factories\GalleryFactory;
use Database\Factories\GalleryLanguageFactory;
use Database\Factories\MenuFactory;
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

    public function test_gallery_index()
    {
        $collection = CollectionFactory::new()->galleryType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebGalleriesController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_sub_pages_gallery_index()
    {
        $collection = CollectionFactory::new()->galleryType()->create();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebGalleriesController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_gallery_photos_show()
    {
        [$collection, $gallery] = $this->createGallery('photos');

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions($path = $page->slug . '/' . $gallery->slug);

        $response = $this->get($path);

        $page->delete();
        $menu->delete();
        $gallery->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebPhotosController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_sub_pages_gallery_photos_show()
    {
        [$collection, $gallery] = $this->createGallery('photos');

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
                . '/' . $gallery->slug
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $gallery->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebPhotosController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_gallery_videos_show()
    {
        [$collection, $gallery] = $this->createGallery('videos');

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions($path = $page->slug . '/' . $gallery->slug);

        $response = $this->get($path);

        $page->delete();
        $menu->delete();
        $gallery->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebVideosController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_sub_pages_gallery_videos_show()
    {
        [$collection, $gallery] = $this->createGallery('videos');

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('galleries')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
                . '/' . $gallery->slug
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $gallery->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebVideosController', 'method' => 'index'
        ]);

        $response->assertOk();
    }
}
