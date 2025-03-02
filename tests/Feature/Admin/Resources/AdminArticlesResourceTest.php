<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Article\Article;
use App\Models\Collection;

class AdminArticlesResourceTest extends TestAdminResources
{
    public function test_admin_articles_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('articles.index', [
            $this->getCollectionModel('articles')->id
        ]));

        $response->assertOk();
    }

    public function test_admin_articles_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('articles.create', [
            $this->getCollectionModel('articles')->id
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_articles_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('articles.store', [
            $this->getCollectionModel('articles')->id
        ]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_articles_resource_edit()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('articles.edit', [
            $this->getCollectionModel('articles')->id, (new Article)->valueOrFail('id')
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_articles_resource_update()
    {
        $article = (new Article)->firstOrFail();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('articles.update', [
            $article->collection_id, $article->id
        ]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_articles_resource_validate_title_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('articles.store', [
            $this->getCollectionModel('articles')->id
        ]), [
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_articles_resource_validate_slug_unique()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('articles.store', [
            $this->getCollectionModel('articles')->id
        ]), [
            'slug' => (new Article)->valueOrfail('slug')
        ]);

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_articles_resource_visibility()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('articles.visibility', [
            (new Article)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_articles_resource_update_position()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('articles.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_articles_resource_transfer()
    {
        $article = (new Article)->firstOrFail();

        $collectionId = (new Collection)->whereKeyNot($article->collection_id)->value('id');

        if (is_null($collectionId)) {
            $collectionId = $this->createCollectionModel('articles')->id;
        }

        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('articles.transfer', [
            $article->collection_id
        ]), [
            'id' => $article->id,
            'column' => 'collection_id',
            'column_value' => $collectionId
        ]);

        $response->assertFound();
    }

    public function test_admin_articles_resource_destroy()
    {
        $article = (new Article)->firstOrFail();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('articles.destroy', [
            $article->collection_id, $article->id
        ]));

        $response->assertFound();
    }
}
