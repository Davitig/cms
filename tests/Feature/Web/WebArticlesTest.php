<?php

namespace Tests\Feature\Web;

use Database\Factories\Article\ArticleFactory;
use Database\Factories\Article\ArticleLanguageFactory;
use Database\Factories\CollectionFactory;
use Database\Factories\MenuFactory;
use Tests\Feature\DynamicRoutesTrait;
use Tests\TestCase;

class WebArticlesTest extends TestCase
{
    use DynamicRoutesTrait;

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

        $data = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebArticlesController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_sub_pages_article_index()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebArticlesController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_article_show()
    {
        [$collection, $article] = $this->createArticle();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions($path = $page->slug . '/' . $article->slug);

        $response = $this->get($path);

        $page->delete();
        $menu->delete();
        $article->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebArticlesController', 'method' => 'show'
        ]);

        $response->assertOk();
    }

    public function test_sub_pages_article_show()
    {
        [$collection, $article] = $this->createArticle();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('articles')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
            . '/' . $article->slug
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $article->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebArticlesController', 'method' => 'show'
        ]);

        $response->assertOk();
    }
}
