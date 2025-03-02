<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Article\Article;
use App\Models\Collection;
use App\Models\Event\Event;
use App\Models\Gallery\Gallery;
use App\Models\Menu;
use App\Models\Page\Page;
use Tests\Feature\Admin\TestAdmin;

class TestAdminResources extends TestAdmin
{
    /**
     * Create a new collection model.
     *
     * @param  string  $type
     * @return \App\Models\Collection
     */
    public function createCollectionModel(string $type): Collection
    {
        return (new Collection)->create([
            'title' => fake()->sentence(2),
            'type' => $type,
            'admin_order_by' => 'position',
            'admin_sort' => 'asc',
            'admin_per_page' => 20,
            'web_order_by' => 'created_at',
            'web_sort' => 'asc',
            'web_per_page' => 20
        ]);
    }

    /**
     * Get the collection model.
     *
     * @param  string  $type
     * @return \App\Models\Collection
     */
    public function getCollectionModel(string $type): Collection
    {
        return (new Collection)->byType($type)->first() ?: $this->createCollectionModel($type);
    }

    /**
     * Create a new gallery model.
     *
     * @param  string  $type
     * @return \App\Models\Gallery\Gallery
     */
    public function createGalleryModel(string $type): Gallery
    {
        return (new Gallery)->create([
            'collection_id' => $this->getCollectionModel('galleries')->id,
            'slug' => fake()->slug(2),
            'title' => fake()->sentence(2),
            'type' => $type,
            'admin_order_by' => 'position',
            'admin_sort' => 'asc',
            'admin_per_page' => 20,
            'web_order_by' => 'created_at',
            'web_sort' => 'asc',
            'web_per_page' => 20
        ]);
    }

    /**
     * Get the gallery model.
     *
     * @param  string  $type
     * @return \App\Models\Gallery\Gallery
     */
    public function getGalleryModel(string $type): Gallery
    {
        return (new Gallery)->byType($type)->first() ?: $this->createGalleryModel($type);
    }

    /**
     * Create a new page model.
     *
     * @return \App\Models\Page\Page
     */
    public function createPageModel(): Page
    {
        $menuId = (
            (new Menu)->first() ?: (new Menu)->create(['title' => 'List of Pages'])
        )->id;

        return (new Page)->create([
            'menu_id' => $menuId,
            'slug' => fake()->slug(2),
            'title' => fake()->sentence(2),
            'type' => 'page'
        ]);
    }

    /**
     * Get the page model.
     *
     * @return \App\Models\Page\Page
     */
    public function getPageModel(): Page
    {
        return (new Page)->first() ?: $this->createPageModel();
    }

    /**
     * Create a new article model.
     *
     * @return \App\Models\Article\Article
     */
    public function createArticleModel(): Article
    {
        return (new Article)->create([
            'collection_id' => $this->getCollectionModel('articles')->id,
            'slug' => fake()->slug(2),
            'title' => fake()->sentence(2)
        ]);
    }

    /**
     * Get the article model.
     *
     * @return \App\Models\Article\Article
     */
    public function getArticleModel(): Article
    {
        return (new Article)->first() ?: $this->createArticleModel();
    }

    /**
     * Create a new event model.
     *
     * @return \App\Models\Event\Event
     */
    public function createEventModel(): Event
    {
        return (new Event)->create([
            'collection_id' => $this->getCollectionModel('events')->id,
            'slug' => fake()->slug(2),
            'title' => fake()->sentence(2)
        ]);
    }

    /**
     * Get the event model.
     *
     * @return \App\Models\Event\Event
     */
    public function getEventModel(): Event
    {
        return (new Event)->first() ?: $this->createEventModel();
    }
}
