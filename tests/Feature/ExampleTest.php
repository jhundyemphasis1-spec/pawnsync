<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_index_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Search Scrapboard Codes');
        $response->assertSee('Login');
    }
}
