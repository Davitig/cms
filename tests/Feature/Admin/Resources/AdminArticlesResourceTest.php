<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Article\Article;

class AdminArticlesResourceTest extends TestAdminResources
{
    public function test_admin_articles_resource_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('articles.index', [
            $this->createCollectionModel('articles')->id
        ]));

        $response->assertOk();
    }

    public function test_admin_articles_resource_create()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('articles.create', [
            $this->getCollectionModel()->id
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_articles_resource_store()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('articles.store', [
            $this->getCollectionModel()->id
        ]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_articles_resource_edit()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('articles.edit', [
            $this->getCollectionModel()->id, (new Article)->valueOrFail('id')
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_articles_resource_update()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('articles.update', [
            $this->getCollectionModel()->id, (new Article)->valueOrFail('id')
        ]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_articles_resource_destroy()
    {
        $response = $this->actingAs($this->getUser())->delete(cms_route('articles.destroy', [
            $this->getCollectionModel()->id, (new Article)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_articles_resource_validate_title_required()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('articles.store', [
            $this->getCollectionModel()->id, (new Article)->valueOrFail('id')
        ]), [
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_articles_resource_validate_slug_unique()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('articles.store', [
            $this->getCollectionModel()->id, (new Article)->valueOrFail('id')
        ]), [
            'slug' => (new Article)->firstOrFail()->slug
        ]);

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_articles_resource_visibility()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('articles.visibility', [
            (new Article)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_articles_resource_update_position()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('articles.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_articles_resource_transfer()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('articles.transfer', [
            ($article = (new Article)->firstOrFail())->collection_id
        ]), [
            'id' => $article->id,
            'column' => 'collection_id',
            'column_value' => $this->createCollectionModel('articles')->id
        ]);

        $response->assertFound();
    }
}
