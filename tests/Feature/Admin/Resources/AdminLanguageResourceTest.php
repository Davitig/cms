<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Language;
use Database\Factories\LanguageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminLanguageResourceTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_languages_resource_index()
    {
        LanguageFactory::new()->count(3)->exclude($this->envLanguages, 3)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('languages.index'));

        $response->assertOk();
    }

    public function test_admin_languages_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('languages.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_languages_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('languages.store'), [
            'language' => 'ab',
            'short_name' => 'ab',
            'full_name' => 'Test language',
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_edit()
    {
        $language = LanguageFactory::new()->exclude($this->envLanguages)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('languages.edit', [$language->id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_languages_resource_update()
    {
        $language = LanguageFactory::new()->exclude($this->envLanguages)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('languages.update', [$language->id]), [
            'language' => 'ab',
            'short_name' => 'ab',
            'full_name' => 'Test language',
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_destroy()
    {
        $language = LanguageFactory::new()->exclude($this->envLanguages)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete($this->cmsRoute('languages.destroy', [$language->id]));

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_validate_unique()
    {
        $language = LanguageFactory::new()->exclude($this->envLanguages)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('languages.store'), [
            'language' => $language->language
        ]);

        $response->assertFound()->assertSessionHasErrors(['language']);
    }

    public function test_admin_languages_resource_validate_string_length()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('languages.store'), [
            'language' => 'e',
            'short_name' => 'e',
            'full_name' => 'English',
        ]);

        $response->assertFound()->assertSessionHasErrors(['language', 'short_name']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_languages_update_main()
    {
        $language = LanguageFactory::new()->exclude($this->envLanguages)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('languages.updateMain'), ['id' => $language->id]);

        $response->assertOk()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_main_is_unique()
    {
        $languages = LanguageFactory::new()
            ->times(2)
            ->main()
            ->exclude($this->envLanguages, 2)
            ->create();

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('languages.updateMain'), ['id' => $languages->first()->id]);

        $count = (new Language)->whereMain(1)->count();

        $this->assertEquals(1, $count);
    }

    public function test_admin_languages_update_main_validate_id_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('languages.updateMain'), [
            // empty data
        ]);

        $response->assertUnprocessable();
    }
}
