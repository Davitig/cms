<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Language;
use Database\Factories\LanguageFactory;
use Tests\Feature\Admin\TestAdmin;

class AdminLanguagesResourceTest extends TestAdmin
{
    public function test_admin_languages_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('languages.index'));

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
        (new Language)->whereLanguage('te')->delete();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('languages.store'), [
            'language' => 'te',
            'short_name' => 'te',
            'full_name' => 'Test language',
        ]);

        (new Language)->whereLanguage('te')->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_edit()
    {
        // delete languages created from the setUp()
        (new Language)->newQuery()->delete();

        $language = LanguageFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('languages.edit', [$language->id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_languages_resource_update()
    {
        // delete languages created from the setUp()
        (new Language)->newQuery()->delete();

        $language = LanguageFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('languages.update', [$language->id]), [
            'language' => 'te',
            'short_name' => 'te',
            'full_name' => 'Test language',
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_destroy()
    {
        // delete languages created from the setUp()
        (new Language)->newQuery()->delete();

        $language = LanguageFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('languages.destroy', [$language->id]));

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_validate_unique()
    {
        // delete languages created from the setUp()
        (new Language)->newQuery()->delete();

        $language = LanguageFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('languages.store'), [
            'language' => $language->language
        ]);

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
        // delete languages created from the setUp()
        (new Language)->newQuery()->delete();

        $language = LanguageFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('languages.updateMain'), ['id' => $language->id]);

        $response->assertOk()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_main_is_unique()
    {
        // delete languages created from the setUp()
        (new Language)->newQuery()->delete();

        $languages = LanguageFactory::new()->times(2)->main(1)->create();

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('languages.updateMain'), ['id' => $languages->first()->id]);

        $this->assertEquals(1, (new Language)->whereMain(1)->count());
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
