<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Translation;
use Database\Factories\TranslationFactory;
use Database\Factories\TranslationLanguageFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;
use Tests\Feature\CreatesLanguageProvider;

class AdminTranslationResourceTest extends TestAdmin
{
    use RefreshDatabase, CreatesLanguageProvider;

    /**
     * Create a new translation.
     *
     * @param  int|null  $times
     * @return \App\Models\Translation|\Illuminate\Database\Eloquent\Collection
     */
    protected function createTranslation(?int $times = null): Translation|Collection
    {
        return TranslationFactory::new()->count($times)->has(
            TranslationLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])), 'languages'
        )->create();
    }

    public function test_admin_translations_resource_index()
    {
        $translations = $this->createTranslation(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('translations.index'));

        $translations->map->delete();

        $response->assertOk();
    }

    public function test_admin_translations_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('translations.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_translations_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('translations.store'), [
            'code' => str($word = fake()->unique()->word())->snake()->toString(),
            'value' => $word
        ]);

        (new Translation)->newQuery()->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_translations_resource_edit()
    {
        $translation = $this->createTranslation();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('translations.edit', [$translation->id]));

        $translation->delete();

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
        )->put(cms_route('translations.update', [$translation->id]), [
            'code' => str($word = fake()->word())->snake()->toString(),
            'value' => $word
        ]);

        $translation->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_translations_resource_validate_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('translations.store'), [
            'code' => str(fake()->word())->snake()->toString()
        ]);

        $response->assertFound()->assertSessionHasErrors(['value']);
    }

    public function test_admin_translations_resource_form_get()
    {
        $translation = $this->createTranslation();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('translations.form', [
            'code' => $translation->code
        ]));

        $translation->delete();

        $response->assertOk();
    }

    public function test_admin_translations_resource_form_post()
    {
        $translation = $this->createTranslation();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('translations.form.post'), [
            'id' => $translation->id,
            'code' => str($word = fake()->word())->snake()->toString(),
            'value' => $word
        ]);

        $translation->delete();

        $response->assertOk()->assertJson([]);
    }

    public function test_admin_translations_resource_destroy()
    {
        $translation = $this->createTranslation();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('translations.destroy', [$translation->id]));

        $translation->delete();

        $response->assertFound();
    }
}
