<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Article\ArticleFile;
use Database\Factories\Article\ArticleFactory;
use Database\Factories\Article\ArticleFileFactory;
use Database\Factories\Article\ArticleFileLanguageFactory;
use Database\Factories\CollectionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminArticleFileResourceTest extends TestAdmin
{
    use RefreshDatabase;

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

        return array_merge([$article], ($files ? [$files] : []));
    }

    public function test_admin_article_files_resource_index()
    {
        [$article] = $this->createArticleFiles(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('articles.files.index', [$article->id]));

        $response->assertOk();
    }

    public function test_admin_article_files_resource_create()
    {
        [$article] = $this->createArticleFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson($this->cmsRoute('articles.files.create', [$article->id]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_article_files_resource_store()
    {
        [$article] = $this->createArticleFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('articles.files.store', [$article->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_article_files_resource_edit()
    {
        [$article, $file] = $this->createArticleFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson($this->cmsRoute('articles.files.edit', [$article->id, $file->id]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_article_files_resource_update()
    {
        [$article, $file] = $this->createArticleFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('articles.files.update', [$article->id, $file->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_article_files_resource_validate_required()
    {
        [$article] = $this->createArticleFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('articles.files.store', [$article->id]), [
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_article_files_resource_visibility()
    {
        [$article, $file] = $this->createArticleFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('articles.files.visibility', [$file->id]));

        $response->assertFound();
    }

    public function test_admin_article_files_resource_update_position()
    {
        [$article, $files] = $this->createArticleFiles(5);

        $data = $ids = [];
        $startItem = $files->first();
        $endItem = $files->last();

        $data[] = ['id' => $ids[] = $startItem->id, 'pos' => $endItem->position];
        foreach ($files as $file) {
            if ($file->id == $startItem->id || $file->id == $endItem->id) {
                continue;
            }

            $data[] = ['id' => $ids[] = $file->id, 'pos' => $file->position - 1];
        }
        $data[] = ['id' => $ids[] = $endItem->id, 'pos' => $endItem->position - 1];

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('articles.files.positions'), [
            'start_id' => $startItem->id,
            'end_id' => $endItem->id,
            'foreign_key' => 'article_id'
        ]);

        $updatedData = (new ArticleFile)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $this->assertSame($data, $updatedData);
    }

    public function test_admin_article_files_resource_destroy()
    {
        [$article, $file] = $this->createArticleFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete($this->cmsRoute('articles.files.destroy', [$article->id, $file->id]));

        $response->assertFound();
    }
}
