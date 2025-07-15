<?php

namespace Tests\Feature\Web;

use App\Models\Product\Product;
use Database\Factories\MenuFactory;
use Database\Factories\Product\ProductFactory;
use Database\Factories\Product\ProductLanguageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Request;
use Tests\Feature\CreatesLanguageProvider;
use Tests\Feature\InteractsWithDynamicPage;
use Tests\TestCase;

class WebProductTest extends TestCase
{
    use RefreshDatabase, CreatesLanguageProvider, InteractsWithDynamicPage;

    /**
     * Create a new product.
     *
     * @return \App\Models\Product\Product
     */
    protected function createProduct(): Product
    {
        return ProductFactory::new()->has(
            ProductLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])),
            'languages'
        )->create();
    }

    public function test_product_index()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('products')
        );

        $route = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $this->assertSame([
            'controller' => 'WebProductController', 'method' => 'index'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_product_index_custom_request_method()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('products')
        );

        $this->app['config']->set('cms.type_request_methods', [
            Request::METHOD_POST => ['products@index' => 'testPostMethod']
        ]);

        $route = $this->getDynamicPageRouteActions($page->slug, Request::METHOD_POST);

        $response = $this->get($page->slug);

        $this->assertSame([
            'controller' => 'WebProductController', 'method' => 'testPostMethod'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_product_index_tabs()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('products')
        );

        $this->app['config']->set('cms.tabs.GET.products@index', [
            'test-uri' => 'testTabMethod'
        ]);

        $route = $this->getDynamicPageRouteActions($page->slug . '/test-uri');

        $this->assertSame([
            'controller' => 'WebProductController', 'method' => 'testTabMethod'
        ], $this->getActionsFromRoute($route));
    }

    public function test_product_index_tabs_with_parameter()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('products')
        );

        $this->app['config']->set('cms.tabs.POST.products@index', [
            'test-uri/{id}' => 'testTabPostMethodWithParameter'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/test-uri/' . rand(5, 10), Request::METHOD_POST
        );

        $this->assertSame([
            'controller' => 'WebProductController',
            'method' => 'testTabPostMethodWithParameter'
        ], $this->getActionsFromRoute($route));
    }

    public function test_product_index_sub_pages()
    {
        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('products')
        );

        $route = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
        );

        $response = $this->get($path);

        $this->assertSame([
            'controller' => 'WebProductController', 'method' => 'index'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_product_show()
    {
        $product = $this->createProduct();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('products')
        );

        $route = $this->getDynamicPageRouteActions($path = $page->slug . '/' . $product->slug);

        $response = $this->get($path);

        $this->assertSame([
            'controller' => 'WebProductController', 'method' => 'show'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_product_show_custom_request_method()
    {
        $product = $this->createProduct();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('products')
        );

        $this->app['config']->set('cms.type_request_methods', [
            Request::METHOD_PUT => ['products@show' => 'testPutMethod']
        ]);

        $route = $this->getDynamicPageRouteActions(
            $path = $page->slug . '/' . $product->slug, Request::METHOD_PUT
        );

        $response = $this->get($path);

        $this->assertSame([
            'controller' => 'WebProductController', 'method' => 'testPutMethod'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_product_show_tabs()
    {
        $product = $this->createProduct();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('products')
        );

        $this->app['config']->set('cms.tabs.PUT.products@show', [
            'test-uri' => 'testTabPutMethod'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/' . $product->slug . '/test-uri', Request::METHOD_PUT
        );

        $this->assertSame([
            'controller' => 'WebProductController', 'method' => 'testTabPutMethod'
        ], $this->getActionsFromRoute($route));
    }

    public function test_product_show_tabs_with_parameter()
    {
        $product = $this->createProduct();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('products')
        );

        $this->app['config']->set('cms.tabs.DELETE.products@show', [
            'test-uri/{id}' => 'testTabDeleteMethodWithParameter'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/' . $product->slug . '/test-uri/' . rand(5, 10),
            Request::METHOD_DELETE
        );

        $this->assertSame([
            'controller' => 'WebProductController',
            'method' => 'testTabDeleteMethodWithParameter'
        ], $this->getActionsFromRoute($route));
    }

    public function test_product_show_sub_pages()
    {
        $product = $this->createProduct();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('products')
        );

        $route = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
                . '/' . $product->slug
        );

        $response = $this->get($path);

        $this->assertSame([
            'controller' => 'WebProductController', 'method' => 'show'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }
}
