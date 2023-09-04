<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SalesControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_seller_get_endpoint(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
