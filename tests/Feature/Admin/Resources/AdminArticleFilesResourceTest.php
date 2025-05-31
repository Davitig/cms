<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Article\ArticleFile;
use Database\Factories\Article\ArticleFactory;
use Database\Factories\Article\ArticleFileFactory;
use Database\Factories\Article\ArticleFileLanguageFactory;
use Database\Factories\CollectionFactory;
use Tests\Feature\Admin\TestAdmin;

class AdminArticleFilesResourceTest extends TestAdmin
{
    /**
     * Create a new article files.
     *
     * @param  int|null  $times
     * @param  bool  $createFiles
     * @return array
     */
    protected function createArticleFiles(?int $times = null, bool $createFiles = true): array
    {
        $collection = CollectionFactory::new()->articleType()->create();

        $article = ArticleFactory::new()->create(['collection_id' => $collection->id]);

        if ($createFiles) {
            $files = ArticleFileFactory::new()->count($times)->has(
                ArticleFileLanguageFactory::times(language()->count())
                    ->sequence(...apply_languages([])),
                'languages'
            )->create(['article_id' => $article->id]);
        } else {
            $files = null;
        }

        return array_merge([$collection, $article], ($files ? [$files] : []));
    }

    public function test_admin_article_files_resource_index()
    {
        [$collection, $article] = $this->createArticleFiles(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('articles.files.index', [$article->id]));

        $article->delete();
        $collection->delete();

        $response->assertOk();
    }

    public function test_admin_article_files_resource_create()
    {
        [$collection, $article] = $this->createArticleFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('articles.files.create', [$article->id]));

        $article->delete();
        $collection->delete();

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_article_files_resource_store()
    {
        [$collection, $article] = $this->createArticleFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('articles.files.store', [$article->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $article->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_article_files_resource_edit()
    {
        [$collection, $article, $file] = $this->createArticleFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('articles.files.edit', [$article->id, $file->id]));

        $article->delete();
        $collection->delete();

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_article_files_resource_update()
    {
        [$collection, $article, $file] = $this->createArticleFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('articles.files.update', [$article->id, $file->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $article->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_article_files_resource_validate_required()
    {
        [$collection, $article] = $this->createArticleFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('articles.files.store', [$article->id]), [
            'file' => fake()->imageUrl()
        ]);

        $article->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_article_files_resource_visibility()
    {
        [$collection, $article, $file] = $this->createArticleFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('articles.files.visibility', [$file->id]));

        $article->delete();
        $collection->delete();

        $response->assertFound();
    }

    public function test_admin_article_files_resource_update_position()
    {
        [$collection, $article, $files] = $this->createArticleFiles(3);

        $newData = $ids = [];

        foreach ($files as $file) {
            $newData[] = ['id' => $ids[] = $file->id, 'pos' => $file->position + 1];
        }

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('articles.files.updatePosition'), ['data' => $newData]);

        $updatedData = (new ArticleFile)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $article->delete();
        $collection->delete();

        $this->assertSame($newData, $updatedData);
    }

    public function test_admin_article_files_resource_destroy()
    {
        [$collection, $article, $file] = $this->createArticleFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('articles.files.destroy', [$article->id, $file->id]));

        $article->delete();
        $collection->delete();

        $response->assertFound();
    }
}
