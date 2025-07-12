<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Article\Article;
use Database\Factories\Article\ArticleFactory;
use Database\Factories\Article\ArticleLanguageFactory;
use Database\Factories\CollectionFactory;
use Tests\Feature\Admin\TestAdmin;

class AdminArticleResourceTest extends TestAdmin
{
    /**
     * Create a new articles.
     *
     * @param  int|null  $times
     * @return array
     */
    protected function createArticles(?int $times = null): array
    {
        $collection = CollectionFactory::new()->articleType()->create();

        $articles = ArticleFactory::new()->count($times)->has(
            ArticleLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])),
            'languages'
        )->collectionId($collection->id)->create();

        return [$collection, $articles];
    }

    public function test_admin_articles_resource_index()
    {
        [$collection, $articles] = $this->createArticles(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('articles.index', [$collection->id]));

        $articles->map->delete();
        $collection->delete();

        $response->assertOk();
    }

    public function test_admin_articles_resource_create()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('articles.create', [$collection->id]));

        $collection->delete();

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_articles_resource_store()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('articles.store', [$collection->id]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2),
            'created_at' => now()->toDateTimeString()
        ]);

        (new Article)->collectionId($collection->id)->firstOrFail()->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_articles_resource_edit()
    {
        [$collection, $article] = $this->createArticles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('articles.edit', [$collection->id, $article->id]));

        $article->delete();
        $collection->delete();

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_articles_resource_update()
    {
        [$collection, $article] = $this->createArticles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('articles.update', [$collection->id, $article->id]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2),
            'created_at' => now()->toDateTimeString()
        ]);

        $article->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_articles_resource_validate_title_required()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('articles.store', [$collection->id]), [
            'slug' => fake()->slug(2)
        ]);

        $collection->delete();

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_articles_resource_validate_slug_unique()
    {
        [$collection, $article] = $this->createArticles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('articles.store', [$collection->id]), [
            'slug' => $article->slug
        ]);

        $article->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_articles_resource_visibility()
    {
        [$collection, $article] = $this->createArticles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('articles.visibility', [$article->id]));

        $article->delete();
        $collection->delete();

        $response->assertFound();
    }

    public function test_admin_articles_resource_update_position()
    {
        [$collection, $articles] = $this->createArticles(5);

        $data = $ids = [];
        $startItem = $articles->first();
        $endItem = $articles->last();

        $data[] = ['id' => $ids[] = $startItem->id, 'pos' => $endItem->position];
        foreach ($articles as $file) {
            if ($file->id == $startItem->id || $file->id == $endItem->id) {
                continue;
            }

            $data[] = ['id' => $ids[] = $file->id, 'pos' => $file->position - 1];
        }
        $data[] = ['id' => $ids[] = $endItem->id, 'pos' => $endItem->position - 1];

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('articles.positions'), [
            'start_id' => $startItem->id,
            'end_id' => $endItem->id,
            'foreign_key' => 'collection_id'
        ]);

        $updatedData = (new Article)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $articles->map->delete();
        $collection->delete();

        $this->assertSame($data, $updatedData);
    }

    public function test_admin_articles_resource_transfer()
    {
        [$collection, $article] = $this->createArticles();

        $newCollection = CollectionFactory::new()->articleType()->create();

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('articles.transfer', [$collection->id]), [
            'id' => $article->id,
            'column' => 'collection_id',
            'column_value' => $newCollection->id
        ]);

        $updatedArticleCollectionId = (new Article)->whereKey($article->id)
            ->value('collection_id');

        $article->delete();
        $collection->delete();
        $newCollection->delete();

        $this->assertSame($newCollection->id, $updatedArticleCollectionId);
    }

    public function test_admin_articles_resource_destroy()
    {
        [$collection, $article] = $this->createArticles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('articles.destroy', [$collection->id, $article->id]));

        $article->delete();
        $collection->delete();

        $response->assertFound();
    }
}
