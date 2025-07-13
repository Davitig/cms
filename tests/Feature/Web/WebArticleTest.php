<?php

namespace Tests\Feature\Web;

use Database\Factories\Article\ArticleFactory;
use Database\Factories\Article\ArticleLanguageFactory;
use Database\Factories\CollectionFactory;
use Database\Factories\MenuFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Request;
use Tests\Feature\CreatesLanguageService;
use Tests\Feature\InteractsWithDynamicPage;
use Tests\TestCase;

class WebArticleTest extends TestCase
{
    use RefreshDatabase, CreatesLanguageService, InteractsWithDynamicPage;

    /**
     * Create a new article.
     *
     * @return array
     */
    protected function createArticle(): array
    {
        $collection = CollectionFactory::new()->articleType()->create();
        $article = ArticleFactory::new()->has(
            ArticleLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])),
            'languages'
        )->collectionId($collection->id)->create();

        return [$collection, $article];
    }

    public function test_article_index()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $this->assertSame([
            'controller' => 'WebArticleController', 'method' => 'index'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_article_index_custom_request_method()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $this->app['config']->set('cms.type_request_methods', [
            Request::METHOD_POST => ['articles@index' => 'testPostMethod']
        ]);

        $route = $this->getDynamicPageRouteActions($page->slug, Request::METHOD_POST);

        $response = $this->get($page->slug);

        $this->assertSame([
            'controller' => 'WebArticleController', 'method' => 'testPostMethod'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_article_index_tabs()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.GET.articles@index', [
            'test-uri' => 'testTabMethod'
        ]);

        $route = $this->getDynamicPageRouteActions($page->slug . '/test-uri');

        $this->assertSame([
            'controller' => 'WebArticleController', 'method' => 'testTabMethod'
        ], $this->getActionsFromRoute($route));
    }

    public function test_article_index_tabs_with_parameter()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.POST.articles@index', [
            'test-uri/{id}' => 'testTabPostMethodWithParameter'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/test-uri/' . rand(5, 10), Request::METHOD_POST
        );

        $this->assertSame([
            'controller' => 'WebArticleController',
            'method' => 'testTabPostMethodWithParameter'
        ], $this->getActionsFromRoute($route));
    }

    public function test_article_index_sub_pages()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
        );

        $response = $this->get($path);

        $this->assertSame([
            'controller' => 'WebArticleController', 'method' => 'index'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_article_show()
    {
        [$collection, $article] = $this->createArticle();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions($path = $page->slug . '/' . $article->slug);

        $response = $this->get($path);

        $this->assertSame([
            'controller' => 'WebArticleController', 'method' => 'show'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_article_show_custom_request_method()
    {
        [$collection, $article] = $this->createArticle();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $this->app['config']->set('cms.type_request_methods', [
            Request::METHOD_PUT => ['articles@show' => 'testPutMethod']
        ]);

        $route = $this->getDynamicPageRouteActions(
            $path = $page->slug . '/' . $article->slug, Request::METHOD_PUT
        );

        $response = $this->get($path);

        $this->assertSame([
            'controller' => 'WebArticleController', 'method' => 'testPutMethod'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_article_show_tabs()
    {
        [$collection, $article] = $this->createArticle();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.PUT.articles@show', [
            'test-uri' => 'testTabPutMethod'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/' . $article->slug . '/test-uri', Request::METHOD_PUT
        );

        $this->assertSame([
            'controller' => 'WebArticleController', 'method' => 'testTabPutMethod'
        ], $this->getActionsFromRoute($route));
    }

    public function test_article_show_tabs_with_parameter()
    {
        [$collection, $article] = $this->createArticle();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $this->app['config']->set('cms.tabs.DELETE.articles@show', [
            'test-uri/{id}' => 'testTabDeleteMethodWithParameter'
        ]);

        $route = $this->getDynamicPageRouteActions(
            $page->slug . '/' . $article->slug . '/test-uri/' . rand(5, 10),
            Request::METHOD_DELETE
        );

        $this->assertSame([
            'controller' => 'WebArticleController',
            'method' => 'testTabDeleteMethodWithParameter'
        ], $this->getActionsFromRoute($route));
    }

    public function test_article_show_sub_pages()
    {
        [$collection, $article] = $this->createArticle();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $route = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
            . '/' . $article->slug
        );

        $response = $this->get($path);

        $this->assertSame([
            'controller' => 'WebArticleController', 'method' => 'show'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }
}
