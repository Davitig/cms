<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Product\Product;
use Database\Factories\Product\ProductFactory;
use Database\Factories\Product\ProductLanguageFactory;
use Illuminate\Database\Eloquent\Collection;
use Tests\Feature\Admin\TestAdmin;

class AdminProductsResourceTest extends TestAdmin
{
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
        $products = $this->createProducts(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('products.index'));

        $products->map->delete();

        $response->assertOk();
    }

    public function test_admin_products_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('products.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_products_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('products.store'), [
            'title' => fake()->sentence(2),
            'price' => fake()->randomFloat(2, 1, 1000),
            'quantity' => fake()->numberBetween(1, 1000)
        ]);

        (new Product)->orderDesc()->firstOrFail()->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_products_resource_edit()
    {
        $product = $this->createProducts();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('products.edit', [$product->id]));

        $product->delete();

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
        )->put(cms_route('products.update', [$product->id]), [
            'title' => fake()->sentence(2),
            'price' => fake()->randomFloat(2, 1, 1000),
            'quantity' => fake()->numberBetween(1, 1000)
        ]);

        $product->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_products_resource_validate_title_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('products.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_products_resource_validate_slug_unique()
    {
        $product = $this->createProducts();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('products.store'), [
            'slug' => $product->slug
        ]);

        $product->delete();

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_products_resource_visibility()
    {
        $product = $this->createProducts();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('products.visibility', [$product->id]));

        $product->delete();

        $response->assertFound();
    }

    public function test_admin_products_resource_update_position()
    {
        $products = $this->createProducts(3);

        $newData = $ids = [];

        foreach ($products as $product) {
            $newData[] = ['id' => $ids[] = $product->id, 'pos' => $product->position + 1];
        }

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('products.updatePosition'), ['data' => $newData]);

        $updatedData = (new Product)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $products->map->delete();

        $this->assertSame($newData, $updatedData);
    }

    public function test_admin_products_resource_destroy()
    {
        $product = ProductFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('products.destroy', [$product->id]));

        $product->delete();

        $response->assertFound();
    }
}
