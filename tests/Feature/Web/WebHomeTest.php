<?php

namespace Tests\Feature\Web;

use Tests\TestCase;

class WebHomeTest extends TestCase
{
    public function test_home_page_success(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }
}
