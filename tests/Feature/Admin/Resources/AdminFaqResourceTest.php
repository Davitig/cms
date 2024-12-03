<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Faq;

class AdminFaqResourceTest extends TestAdminResources
{
    public function test_admin_faq_resource_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('faq.index', [
            $this->createCollectionModel('faq')->id
        ]));

        $response->assertOk();
    }

    public function test_admin_faq_resource_create()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('faq.create', [
            $this->getCollectionModel('faq')->id
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_faq_resource_store()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('faq.store', [
            $this->getCollectionModel('faq')->id
        ]), [
            'title' => fake()->sentence(2)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_faq_resource_edit()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('faq.edit', [
            $this->getCollectionModel('faq')->id, (new Faq)->valueOrFail('id')
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_faq_resource_update()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('faq.update', [
            $this->getCollectionModel('faq')->id, (new Faq)->valueOrFail('id')
        ]), [
            'title' => fake()->sentence(2)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_faq_resource_destroy()
    {
        (new Faq)->create([
            'collection_id' => $collectionId = $this->getCollectionModel('faq')->id,
            'title' => fake()->sentence(2)
        ]);

        $response = $this->actingAs($this->getUser())->delete(cms_route('faq.destroy', [
            $collectionId, (new Faq)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_faq_resource_validate_title_required()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('faq.store', [
            $this->getCollectionModel('faq')->id
        ]), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_faq_resource_visibility()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('faq.visibility', [
            (new Faq)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_faq_resource_update_position()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('faq.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_faq_resource_transfer()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('faq.transfer', [
            ($faq = (new Faq)->firstOrFail())->collection_id
        ]), [
            'id' => $faq->id,
            'column' => 'collection_id',
            'column_value' => $this->createCollectionModel('faq')->id
        ]);

        $faq->delete();

        $response->assertFound();
    }
}
