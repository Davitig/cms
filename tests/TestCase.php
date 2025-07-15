<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Feature\CreatesLanguageProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (in_array(CreatesLanguageProvider::class, class_uses_recursive($this)) &&
            method_exists($this, 'createLanguageProvider')) {
            $this->createLanguageProvider();
        }
    }
}
