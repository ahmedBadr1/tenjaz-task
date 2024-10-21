<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    // create auth user
    $this->user = User::factory()->create();

    // Create a sample product
    $this->product = Product::factory()->create([
        'name' => 'Test Product',
        'description' => 'This is a test product.',
        'price' => 100.00,
    ]);
});

it('can create a product', function () {
    $this->actingAs($this->user);

    $image = UploadedFile::fake()->image('product.jpg');

    // Define the data for a new product
    $productData = [
        'name' => 'New Product',
        'description' => 'Description for new product.',
        'price' => 200.50,
        "image" => $image,
        "is_active" => true,
    ];

    // Send a POST request to create a new product
    $response = $this->post('/api/v1/products', $productData);

    // Assert that the product was created successfully
    $response->assertStatus(201);
    $response->assertJsonFragment(['name' => 'New Product']);

//    Storage::disk('public')->assertExists('products/' . $image->hashName());


    // Assert that the product exists in the database
    $this->assertDatabaseHas('products', ['name' => 'New Product']);
});

it('can get all products', function () {
    $this->actingAs($this->user);
    // Send a GET request to retrieve all products
    $response = $this->get('/api/v1/products');

    // Assert that the request was successful and that the data is correct
    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Test Product']);
});

it('can get a specific product by id', function () {
    $this->actingAs($this->user);

    // Send a GET request to retrieve the specific product
    $response = $this->getJson('/api/v1/products/' . $this->product->id);

    // Assert that the request was successful and that the data is correct
    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Test Product']);
});

it('can update a product', function () {
    $this->actingAs($this->user);
    // Define new data for the update
    $image = UploadedFile::fake()->image('product.jpg');

    $newData = [
        'name' => 'Updated Product Name',
        'description' => 'Updated description.',
        'price' => 100.50,
        "image" => $image,
        "is_active" => true,
    ];

    // Send a PUT request to update the product
    $response = $this->putJson('/api/v1/products/' . $this->product->id, $newData);

    // Assert that the request was successful
    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Updated Product Name']);

    // Assert that the product was updated in the database
    $this->assertDatabaseHas('products', ['name' => 'Updated Product Name']);
});

it('can delete a product', function () {
    $this->actingAs($this->user);
    // Send a DELETE request to remove the product
    $response = $this->deleteJson('/api/v1/products/' . $this->product->id);

    // Assert that the request was successful
    $response->assertStatus(200);
    $response->assertJsonFragment(['message' => 'Product deleted successfully']);

    // Assert that the product is no longer in the database
    $this->assertDatabaseMissing('products', ['name' => 'Test Product']);
});
