<?php

namespace Tests;

use App\Models\Language;
use Database\Factories\LanguageFactory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        (new Language)->newQuery()->delete();

        DB::update('ALTER TABLE languages AUTO_INCREMENT = 1');

        $languages = LanguageFactory::new()->times(5)->create()
            ->mapWithKeys(function (Language $language) {
                return [$language->language => $language->toArray() + ['url' => '']];
            })->toArray();

        $this->app['config']->set('_app.languages', $languages);
        $this->app['config']->set('_app.language', key($languages));
    }

    protected function tearDown(): void
    {
        (new Language)->newQuery()->delete();

        parent::tearDown();
    }
}
