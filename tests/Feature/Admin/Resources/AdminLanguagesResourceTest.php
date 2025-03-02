<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Language;
use Tests\Feature\Admin\TestAdmin;

class AdminLanguagesResourceTest extends TestAdmin
{
    /**
     * Get the language id.
     *
     * @param  string  $language
     * @return int
     */
    protected function getLanguageId(string $language): int
    {
        return (new Language)->whereLanguage($language)->valueOrFail('id');
    }

    public function test_admin_languages_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('languages.index'));

        $response->assertOk();
    }

    public function test_admin_languages_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
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
            $this->getFullAccessCmsUser()
        )->post(cms_route('languages.store'), [
            'language' => 'te',
            'short_name' => 'te',
            'full_name' => 'Test language name',
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_edit()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('languages.edit', [$this->getLanguageId('te')]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_languages_resource_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('languages.update', [$this->getLanguageId('te')]), [
            'language' => 'te',
            'short_name' => 'te',
            'full_name' => 'Test language full name',
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_destroy()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('languages.destroy', [$this->getLanguageId('te')]));

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_validate_unique()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('languages.store'), [
            'language' => 'en',
            'short_name' => 'en',
            'full_name' => 'English',
        ]);

        $response->assertFound()->assertSessionHasErrors(['language']);
    }

    public function test_admin_languages_resource_validate_string_length()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('languages.store'), [
            'language' => 'e',
            'short_name' => 'e',
            'full_name' => 'English',
        ]);

        $response->assertFound()->assertSessionHasErrors(['language', 'short_name']);
    }
}
