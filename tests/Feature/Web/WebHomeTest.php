<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\CreatesLanguageProvider;
use Tests\TestCase;

class WebHomeTest extends TestCase
{
    use RefreshDatabase, CreatesLanguageProvider;

    public function test_home_page_success(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }
}
