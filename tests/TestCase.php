<?php

namespace Tests;

use App\Models\Language;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! (new Language)->exists()) {
            (new Language)->create([
                'language' => 'en',
                'short_name' => 'en',
                'full_name' => 'English',
                'visible' => 1
            ]);
        }
    }
}
