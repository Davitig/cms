<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Article\Article;
use App\Models\Article\ArticleFile;

class AdminArticleFilesResourceTest extends TestAdminResources
{
    public function test_admin_article_files_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('articles.files.index', [
            $this->getArticleModel()->id
        ]));

        $response->assertOk();
    }

    public function test_admin_article_files_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->getJson(cms_route('articles.files.create', [
            $this->getArticleModel()->id
        ]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_article_files_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('articles.files.store', [
            $this->getArticleModel()->id
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_article_files_resource_edit()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->getJson(cms_route('articles.files.edit', [
            $this->getArticleModel()->id, (new ArticleFile)->valueOrFail('id')
        ]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_article_files_resource_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('articles.files.update', [
            $this->getArticleModel()->id, (new ArticleFile)->valueOrFail('id')
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_article_files_resource_validate_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('articles.files.store', [
            $this->getArticleModel()->id
        ]), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title', 'file']);
    }

    public function test_admin_article_files_resource_visibility()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('articles.files.visibility', [
            (new ArticleFile)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_article_files_resource_update_position()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('articles.files.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_article_files_resource_destroy()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('articles.files.destroy', [
            $this->getArticleModel()->id, (new ArticleFile)->valueOrFail('id')
        ]));

        (new Article)->newQuery()->delete();

        $response->assertFound();
    }
}
