<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Seller;
use Illuminate\Testing\Fluent\AssertableJson;

class SellersControllerTest extends TestCase
{
    /**
     * A basic feature test get seller.
     */
    public function test_seller_get_endpoint(): void
    {
        $sellers = Seller::factory(1)->create();

        $response = $this->getJson('/api/sellers');
        
        $response->assertStatus(200);
        
        $response->assertJson(function (AssertableJson $json) use ($sellers){

            $json->whereType('sellers.0.id','integer');
            $json->whereType('sellers.0.name','string');
            $json->whereType('sellers.0.email','string');
            // $json->whereType('sellers.0.commission','double');

            $json->hasAll(['sellers.0.id','sellers.0.name','sellers.0.email']);

            $seller = $sellers->first();

        });

    }

    /**
     * A basic feature test post seller.
     */
    public function test_seller_post_endpoint(): void
    {
        $seller = Seller::factory(1)->makeOne()->toArray();

        $response = $this->postJson('/api/sellers', $seller);
         
        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) use ($seller){

            $json->whereAll([
                    'Seller.name' => $seller['name'],
                    'Seller.email' => $seller['email'],
                    // 'Seller.commission' => $seller['commission'],
            ])->etc();

        });

    }
}
