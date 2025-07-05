<?php

namespace Tests\Feature\Web;

use App\Models\Product\Product;
use Database\Factories\MenuFactory;
use Database\Factories\Product\ProductFactory;
use Database\Factories\Product\ProductLanguageFactory;
use Symfony\Component\HttpFoundation\Request;
use Tests\Feature\DynamicRoutesTrait;
use Tests\TestCase;

class WebProductTest extends TestCase
{
    use DynamicRoutesTrait;

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

        $page->delete();
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebProductController', 'method' => 'index'
        ]);

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

        $page->delete();
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebProductController', 'method' => 'testPostMethod'
        ]);

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

        $page->delete();
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebProductController', 'method' => 'testTabMethod'
        ]);
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

        $page->delete();
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebProductController',
            'method' => 'testTabPostMethodWithParameter'
        ]);
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

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebProductController', 'method' => 'index'
        ]);

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

        $page->delete();
        $menu->delete();
        $product->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebProductController', 'method' => 'show'
        ]);

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

        $page->delete();
        $menu->delete();
        $product->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebProductController', 'method' => 'testPutMethod'
        ]);

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

        $page->delete();
        $menu->delete();
        $product->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebProductController', 'method' => 'testTabPutMethod'
        ]);
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

        $page->delete();
        $menu->delete();
        $product->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebProductController',
            'method' => 'testTabDeleteMethodWithParameter'
        ]);
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

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $product->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebProductController', 'method' => 'show'
        ]);

        $response->assertOk();
    }
}
