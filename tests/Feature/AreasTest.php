<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AreasTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAll()
    {
        $response = $this->getJson('/api/areas?st=NSW');

        $response->assertStatus(200);

        $json = $response->json();

        $response->assertJsonCount(63, 'data');
    }
}
