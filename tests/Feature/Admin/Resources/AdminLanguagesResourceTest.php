<?php

namespace Feature\Admin\Resources;

use App\Models\Language;
use Tests\TestCase;

class AdminLanguagesResourceTest extends TestCase
{
    /**
     * Get the language id.
     *
     * @param  string  $language
     * @return int
     */
    protected function getLanguageId(string $language): int
    {
        return (new Language)->whereLanguage($language)->firstOrFail()->id;
    }

    public function test_admin_languages_resource_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('languages.index'));

        $response->assertOk();
    }

    public function test_admin_languages_resource_create()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('languages.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_languages_resource_store()
    {
        $this->seed();

        $response = $this->actingAs($this->getUser())->post(cms_route('languages.store'), [
            'language' => 'cn',
            'short_name' => 'cn',
            'full_name' => 'Chinese',
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_edit()
    {
        $id = $this->getLanguageId('cn');

        $response = $this->actingAs($this->getUser())->get(cms_route('languages.edit', [$id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_languages_resource_update()
    {
        $id = $this->getLanguageId('cn');

        $response = $this->actingAs($this->getUser())->put(cms_route('languages.update', [$id]), [
            'language' => 'cn',
            'short_name' => 'cn',
            'full_name' => 'Chinese',
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_languages_resource_destroy()
    {
        $id = $this->getLanguageId('cn');

        $response = $this->actingAs($this->getUser())->delete(cms_route('languages.destroy', [$id]));

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_languages_resource_validate_unique()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('languages.store'), [
            'language' => 'en',
            'short_name' => 'en',
            'full_name' => 'English',
        ]);

        $response->assertFound()->assertSessionHasErrors(['language']);
    }

    public function test_admin_languages_resource_validate_string_length()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('languages.store'), [
            'language' => 'e',
            'short_name' => 'e',
            'full_name' => 'English',
        ]);

        $response->assertFound()->assertSessionHasErrors(['language', 'short_name']);
    }
}
