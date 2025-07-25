<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebHomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_success(): void
    {
        $response = $this->get($this->webRoute('home'));

        $response->assertOk();
    }
}
