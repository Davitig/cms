<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Language;
use Database\Factories\LanguageFactory;
use Tests\Feature\Admin\TestAdmin;

class AdminLanguagesResourceTest extends TestAdmin
{
    public function test_admin_languages_resource_index()
    {
        $languages = LanguageFactory::new()->count(5)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('languages.index'));

        $languages->map->delete();

        $response->assertOk();
    }

    public function test_admin_languages_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('languages.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_languages_resource_store()
    {
        (new Language)->whereLanguage('aa')->delete();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('languages.store'), [
            'language' => 'aa',
            'short_name' => 'aa',
            'full_name' => 'Test language',
        ]);

        (new Language)->whereLanguage('aa')->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_edit()
    {
        $language = LanguageFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('languages.edit', [$language->id]));

        $language->delete();

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_languages_resource_update()
    {
        $language = LanguageFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('languages.update', [$language->id]), [
            'language' => 'aa',
            'short_name' => 'aa',
            'full_name' => 'Test language',
        ]);

        $language->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_destroy()
    {
        $language = LanguageFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('languages.destroy', [$language->id]));

        $language->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_validate_unique()
    {
        $language = LanguageFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('languages.store'), [
            'language' => $language->language
        ]);

        $language->delete();

        $response->assertFound()->assertSessionHasErrors(['language']);
    }

    public function test_admin_languages_resource_validate_string_length()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('languages.store'), [
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
        $language = LanguageFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('languages.updateMain'), ['id' => $language->id]);

        $language->delete();

        $response->assertOk()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_main_is_unique()
    {
        $languages = LanguageFactory::new()->times(2)->main(1)->create();

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('languages.updateMain'), ['id' => $languages->first()->id]);

        $count = (new Language)->whereMain(1)->count();

        $languages->map->delete();

        $this->assertEquals(1, $count);
    }

    public function test_admin_languages_update_main_validate_id_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('languages.updateMain'), [
            // empty data
        ]);

        $response->assertUnprocessable();
    }
}
