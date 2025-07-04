<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\TestCase;

class ServiceUnavailableTest extends TestCase
{
    public function test_503_unavailable_when_no_language_is_active(): void
    {
        $response = $this->get('/');

        $response->assertServiceUnavailable();
    }
}
