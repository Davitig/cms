<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Translation\Translation;
use Database\Factories\Translation\TranslationFactory;
use Database\Factories\Translation\TranslationLanguageFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminTranslationResourceTest extends TestAdmin
{
    use RefreshDatabase;

    /**
     * Create a new translation.
     *
     * @param  int|null  $times
     * @return \App\Models\Translation\Translation|\Illuminate\Database\Eloquent\Collection
     */
    protected function createTranslation(?int $times = null): Translation|Collection
    {
        return TranslationFactory::new()->count($times)->has(
            TranslationLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])),
            'languages'
        )->create();
    }

    public function test_admin_translations_resource_index()
    {
        $this->createTranslation(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('translations.index'));

        $response->assertOk();
    }

    public function test_admin_translations_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson($this->cmsRoute('translations.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_translations_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('translations.store'), [
            'code' => str($word = fake()->unique()->word())->snake()->toString(),
            'value' => $word
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_translations_resource_edit()
    {
        $translation = $this->createTranslation();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson($this->cmsRoute('translations.edit', [$translation->id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_translations_resource_update()
    {
        $translation = $this->createTranslation();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('translations.update', [$translation->id]), [
            'code' => str($word = fake()->word())->snake()->toString(),
            'value' => $word
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_translations_resource_validate_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('translations.store'), [
            'code' => str(fake()->word())->snake()->toString()
        ]);

        $response->assertFound()->assertSessionHasErrors(['value']);
    }

    public function test_admin_translations_resource_form_get()
    {
        $translation = $this->createTranslation();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('translations.form', [
            'code' => $translation->code
        ]));

        $response->assertOk();
    }

    public function test_admin_translations_resource_form_post()
    {
        $translation = $this->createTranslation();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('translations.form.post'), [
            'id' => $translation->id,
            'code' => str($word = fake()->word())->snake()->toString(),
            'value' => $word
        ]);

        $response->assertOk()->assertJson([]);
    }

    public function test_admin_translations_resource_destroy()
    {
        $translation = $this->createTranslation();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete($this->cmsRoute('translations.destroy', [$translation->id]));

        $response->assertFound();
    }
}
