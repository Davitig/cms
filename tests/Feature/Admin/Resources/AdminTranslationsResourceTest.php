<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Translation;

class AdminTranslationsResourceTest extends TestAdminResources
{
    public function test_admin_translations_resource_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('translations.index'));

        $response->assertOk();
    }

    public function test_admin_translations_resource_create()
    {
        $response = $this->actingAs($this->getUser())->getJson(cms_route('translations.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_translations_resource_store()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('translations.store'), [
            'title' => $word = fake()->word(),
            'code' => str($word)->snake()->toString(),
            'value' => $word
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_translations_resource_edit()
    {
        $response = $this->actingAs($this->getUser())->getJson(cms_route('translations.edit', [
            (new Translation)->valueOrFail('id')
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_translations_resource_update()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('translations.update', [
            (new Translation)->valueOrFail('id')
        ]), [
            'title' => $word = fake()->word(),
            'code' => str($word)->snake()->toString(),
            'value' => $word
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_translations_resource_validate_required()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('translations.store'), [
            'title' => fake()->word()
        ]);

        $response->assertFound()->assertSessionHasErrors(['code', 'value']);
    }

    public function test_admin_translations_resource_form_get()
    {
        $response = $this->actingAs($this->getUser())->get(
            cms_route('translations.form', [
                'code' => (new Translation)->valueOrFail('code')
            ])
        );

        $response->assertOk();
    }

    public function test_admin_translations_resource_form_post()
    {
        $response = $this->actingAs($this->getUser())->post(
            cms_route('translations.form.post'), [
                'id' => (new Translation)->valueOrFail('id'),
                'title' => $word = fake()->word(),
                'code' => str($word)->snake()->toString(),
                'value' => $word
            ]
        );

        $response->assertOk()->assertJson([]);
    }

    public function test_admin_translations_resource_destroy()
    {
        $response = $this->actingAs($this->getUser())->delete(cms_route('translations.destroy', [
            (new Translation)->valueOrFail('id')
        ]));

        $response->assertFound();
    }
}
