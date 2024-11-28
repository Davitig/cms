<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_home_page_success(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }
}
