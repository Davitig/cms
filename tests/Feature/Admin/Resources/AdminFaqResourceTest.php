<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Faq;
use Database\Factories\CollectionFactory;
use Database\Factories\Faq\FaqFactory;
use Database\Factories\Faq\FaqLanguageFactory;
use Tests\Feature\Admin\TestAdmin;

class AdminFaqResourceTest extends TestAdmin
{
    /**
     * Create a new FAQs.
     *
     * @param  int|null  $times
     * @return array
     */
    protected function createFaq(?int $times = null): array
    {
        $collection = CollectionFactory::new()->faqType()->create();

        $faq = FaqFactory::new()->count($times)->has(
            FaqLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])),
            'languages'
        )->collectionId($collection->id)->create();

        return [$collection, $faq];
    }

    public function test_admin_faq_resource_index()
    {
        [$collection, $faqList] = $this->createFaq(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('faq.index', [$collection->id]));

        $faqList->map->delete();
        $collection->delete();

        $response->assertOk();
    }

    public function test_admin_faq_resource_create()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('faq.create', [$collection->id]));

        $collection->delete();

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_faq_resource_store()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('faq.store', [$collection->id]), [
            'title' => fake()->sentence(2)
        ]);

        (new Faq)->collectionId($collection->id)->firstOrFail()->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_faq_resource_edit()
    {
        [$collection, $faq] = $this->createFaq();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('faq.edit', [$collection->id, $faq->id]));

        $faq->delete();
        $collection->delete();

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_faq_resource_update()
    {
        [$collection, $faq] = $this->createFaq();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('faq.update', [$collection->id, $faq->id]), [
            'title' => fake()->sentence(2)
        ]);

        $faq->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_faq_resource_validate_title_required()
    {
        $collection = CollectionFactory::new()->faqType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('faq.store', [$collection->id]), [
            // empty data
        ]);

        $collection->delete();

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_faq_resource_visibility()
    {
        [$collection, $faq] = $this->createFaq();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('faq.visibility', [$faq->id]));

        $faq->delete();
        $collection->delete();

        $response->assertFound();
    }

    public function test_admin_faq_resource_update_position()
    {
        [$collection, $faqList] = $this->createFaq(3);

        $newData = $ids = [];

        foreach ($faqList as $faq) {
            $newData[] = ['id' => $ids[] = $faq->id, 'pos' => $faq->position + 1];
        }

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('faq.updatePosition'), ['data' => $newData]);

        $updatedData = (new Faq)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $faqList->map->delete();
        $collection->delete();

        $this->assertSame($newData, $updatedData);
    }

    public function test_admin_faq_resource_transfer()
    {
        [$collection, $faq] = $this->createFaq();

        $newCollection = CollectionFactory::new()->articleType()->create();

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('faq.transfer', [$collection->id]), [
            'id' => $faq->id,
            'column' => 'collection_id',
            'column_value' => $newCollection->id
        ]);

        $updatedFaqCollectionId = (new Faq)->whereKey($faq->id)->value('collection_id');

        $faq->delete();
        $collection->delete();
        $newCollection->delete();

        $this->assertSame($newCollection->id, $updatedFaqCollectionId);
    }

    public function test_admin_faq_resource_destroy()
    {
        [$collection, $faq] = $this->createFaq();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('faq.destroy', [$collection->id, $faq->id]));

        $faq->delete();
        $collection->delete();

        $response->assertFound();
    }
}
