<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAll()
    {
        $response = $this->getJson('/api/products?st=NSW');

        $response->assertStatus(200);

        $json = $response->json();

        $response->assertJsonCount(10, 'data');
    }

    public function testProductRouteBinding()
    {
        $response = $this->getJson('/api/product/5bac22d4cd8d68ad0493af71');

        $response->assertStatus(200);
    }

}
