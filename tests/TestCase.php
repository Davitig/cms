<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Feature\CreatesLanguageService;

abstract class TestCase extends BaseTestCase
{
    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (in_array(CreatesLanguageService::class, class_uses_recursive($this)) &&
            method_exists($this, 'createLanguageService')) {
            $this->createLanguageService();
        }
    }
}
