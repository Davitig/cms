<?php

namespace Tests\Feature\Web;

use Database\Factories\CollectionFactory;
use Database\Factories\Faq\FaqFactory;
use Database\Factories\Faq\FaqLanguageFactory;
use Database\Factories\MenuFactory;
use Tests\Feature\DynamicRoutesTrait;
use Tests\TestCase;

class WebFaqTest extends TestCase
{
    use DynamicRoutesTrait;

    /**
     * Create a new faq.
     *
     * @return array
     */
    protected function createFaq(): array
    {
        $collection = CollectionFactory::new()->faqType()->create();
        $faq = FaqFactory::new()->has(
            FaqLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])),
            'languages'
        )->collectionId($collection->id)->create();

        return [$collection, $faq];
    }

    public function test_faq_index()
    {
        $collection = CollectionFactory::new()->faqType()->create();

        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('faq')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebFaqController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_sub_pages_faq_index()
    {
        $collection = CollectionFactory::new()->faqType()->create();

        $menu = MenuFactory::new()->create();
        $pages = $this->createSubPages(
            $menu->id, null,
            fn ($factory) => $factory->type('faq')->typeId($collection->id)
        );

        $data = $this->getDynamicPageRouteActions(
            $path = implode('/', array_map(fn ($page) => $page->slug, $pages))
        );

        $response = $this->get($path);

        array_map(fn ($page) => $page->delete(), $pages);
        $menu->delete();
        $collection->delete();

        $this->assertSame($data, [
            'controller' => 'WebFaqController', 'method' => 'index'
        ]);

        $response->assertOk();
    }
}
