<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test retrieving all products.
     *
     * @return void
     */
    public function testGetAllProducts()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->json('GET', '/api/products');

        $response->assertStatus(200)
            ->assertJson([]);
    }

    /**
     * Test creating a new product.
     *
     * @return void
     */
    public function testCreateProduct()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->json('POST', '/api/products', [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 10.99,
            'quantity' => 5,
        ]);

        $response->assertStatus(201)
            ->assertJson(['name' => 'Test Product']);
    }

    /**
     * Test updating an existing product.
     *
     * @return void
     */
    public function testUpdateProduct()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $product = Product::factory()->create([
            'name' => 'Initial Product',
            'description' => 'Initial Description',
            'price' => 9.99,
            'quantity' => 10,
        ]);

        $response = $this->json('PUT', '/api/products/' . $product->id, [
            'name' => 'Updated Product',
            'description' => 'Updated Description',
            'price' => 19.99,
            'quantity' => 15,
        ]);

        $response->assertStatus(200)
            ->assertJson(['name' => 'Updated Product']);
    }

    /**
     * Test deleting a product.
     *
     * @return void
     */
    public function testDeleteProduct()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $product = Product::factory()->create();

        $response = $this->json('DELETE', '/api/products/' . $product->id);

        $response->assertStatus(204);
    }
}
