<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Product\Product;
use Database\Factories\Product\ProductFactory;
use Database\Factories\Product\ProductLanguageFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminProductResourceTest extends TestAdmin
{
    use RefreshDatabase;

    /**
     * Create new products.
     *
     * @param  int|null  $times
     * @return \Illuminate\Database\Eloquent\Collection<int, Product>|Product
     */
    protected function createProducts(?int $times = null): Collection|Product
    {
        return ProductFactory::new()->count($times)->has(
            ProductLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])),
            'languages'
        )->create();
    }

    public function test_admin_products_resource_index()
    {
        $this->createProducts(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('products.index'));

        $response->assertOk();
    }

    public function test_admin_products_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('products.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_products_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('products.store'), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2),
            'price' => fake()->randomFloat(2, 1, 1000),
            'quantity' => fake()->numberBetween(1, 1000)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_products_resource_edit()
    {
        $product = $this->createProducts();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('products.edit', [$product->id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_products_resource_update()
    {
        $product = $this->createProducts();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('products.update', [$product->id]), [
            'title' => fake()->sentence(2),
            'price' => fake()->randomFloat(2, 1, 1000),
            'quantity' => fake()->numberBetween(1, 1000)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_products_resource_validate_title_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('products.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_products_resource_validate_slug_unique()
    {
        $product = $this->createProducts();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('products.store'), [
            'slug' => $product->slug
        ]);

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_products_resource_visibility()
    {
        $product = $this->createProducts();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('products.visibility', [$product->id]));

        $response->assertFound();
    }

    public function test_admin_products_resource_update_position()
    {
        $products = $this->createProducts(3);

        $data = $ids = [];
        $startItem = $products->first();
        $endItem = $products->last();

        $data[] = ['id' => $ids[] = $startItem->id, 'pos' => $endItem->position];
        foreach ($products as $file) {
            if ($file->id == $startItem->id || $file->id == $endItem->id) {
                continue;
            }

            $data[] = ['id' => $ids[] = $file->id, 'pos' => $file->position - 1];
        }
        $data[] = ['id' => $ids[] = $endItem->id, 'pos' => $endItem->position - 1];

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('products.positions'), [
            'start_id' => $startItem->id,
            'end_id' => $endItem->id,
        ]);

        $updatedData = (new Product)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $this->assertSame($data, $updatedData);
    }

    public function test_admin_products_resource_destroy()
    {
        $product = ProductFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete($this->cmsRoute('products.destroy', [$product->id]));

        $response->assertFound();
    }
}
