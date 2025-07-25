<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Product\ProductFile;
use Database\Factories\Product\ProductFactory;
use Database\Factories\Product\ProductFileFactory;
use Database\Factories\Product\ProductFileLanguageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminProductFileResourceTest extends TestAdmin
{
    use RefreshDatabase;

    /**
     * Create new product files.
     *
     * @param  int|null  $times
     * @param  bool  $createFiles
     * @return array
     */
    protected function createProductFiles(?int $times = null, bool $createFiles = true): array
    {
        $product = ProductFactory::new()->create();

        if ($createFiles) {
            $files = ProductFileFactory::new()->count($times)->has(
                ProductFileLanguageFactory::times(language()->count())
                    ->sequence(...apply_languages([])),
                'languages'
            )->create(['product_id' => $product->id]);
        } else {
            $files = null;
        }

        return array_merge([$product], ($files ? [$files] : []));
    }

    public function test_admin_product_files_resource_index()
    {
        [$product] = $this->createProductFiles(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('products.files.index', [$product->id]));

        $response->assertOk();
    }

    public function test_admin_product_files_resource_create()
    {
        [$product] = $this->createProductFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson($this->cmsRoute('products.files.create', [$product->id]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_product_files_resource_store()
    {
        [$product] = $this->createProductFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('products.files.store', [$product->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_product_files_resource_edit()
    {
        [$product, $file] = $this->createProductFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson($this->cmsRoute('products.files.edit', [$product->id, $file->id]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_product_files_resource_update()
    {
        [$product, $file] = $this->createProductFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('products.files.update', [$product->id, $file->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_product_files_resource_validate_required()
    {
        [$product] = $this->createProductFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('products.files.store', [$product->id]), [
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_product_files_resource_visibility()
    {
        [$product, $file] = $this->createProductFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('products.files.visibility', [$file->id]));

        $response->assertFound();
    }

    public function test_admin_product_files_resource_update_position()
    {
        [$product, $files] = $this->createProductFiles(5);

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
        )->put($this->cmsRoute('products.files.positions'), [
            'start_id' => $startItem->id,
            'end_id' => $endItem->id,
            'foreign_key' => 'product_id'
        ]);

        $updatedData = (new ProductFile)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $this->assertSame($data, $updatedData);
    }

    public function test_admin_product_files_resource_destroy()
    {
        [$product, $file] = $this->createProductFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete($this->cmsRoute('products.files.destroy', [
            $product->id, $file->id
        ]));

        $response->assertFound();
    }
}
