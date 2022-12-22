<?php

namespace Tests\Unit;

use App\Models\Md\Product;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use WithFaker;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function test_add_product()
    {
        $ceoData = [
            "name" => $this->faker->sentence(1),
        ];

        $this->json('POST', 'api/md/product/create', $ceoData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'status' => 'S',
                'response' => 'Data saved successfully',
            ]);
    }

    public function test_duplicate_product()
    {
        $ceoData = [
            "name" => "Monitor"
        ];

        $this->json('POST', 'api/md/product/create', $ceoData, ['Accept' => 'application/json']);
        $this->json('POST', 'api/md/product/create', $ceoData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'status' => 'E',
                'response' => array(),
            ]);
    }

    public function test_update_product()
    {
        // $product = Product::factory()->create(
        //     ['name' => 'Wa']
        // );

        $payload = [
            "name" => $this->faker->sentence(2),
        ];

        $this->json('POST', 'api/md/product/update/' . 1, $payload, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'status' => 'S', 
                'response' => 'Data saved successfully',
            ]);
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create();

        $this->json('DELETE', 'api/md/product/' . $product->id, [], ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'status' => 'S', 
                'response' => 'Data deleted successfully',
            ]);
    }
}
