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
    $this->user = User::factory()->normal()->create();
    $this->silverUser = User::factory()->silver()->create();

    $this->goldUser = User::factory()->gold()->create();

    // Create a sample product
    $this->product = Product::factory()->active()->create([
        'name' => 'Test Product',
        'description' => 'This is a test product.',
    ]);
    $this->product->prices()->createMany([
        ["type" => \App\Enums\UserTypes::Normal->value, "price" => 100 ],
        ["type" => \App\Enums\UserTypes::Silver->value, "price" => 80],
        ["type" => \App\Enums\UserTypes::Gold->value, "price" => 60],
    ]);
});

it('can create a product', function () {
    $this->actingAs($this->user);

    $image = UploadedFile::fake()->image('product.jpg');

    // Define the data for a new product
    $productData = [
        'name' => 'New Product',
        'description' => 'Description for new product.',
        "image" => $image,
        "is_active" => true,
        "prices" => [
            ["type" => \App\Enums\UserTypes::Normal->name, "price" => 100],
            ["type" => \App\Enums\UserTypes::Silver->name, "price" => 80],
            ["type" => \App\Enums\UserTypes::Gold->name, "price" => 60]
        ]
    ];

    // Send a POST request to create a new product
    $response = $this->post('/api/v1/products', $productData);

    // Assert that the product was created successfully
    $response->assertStatus(201);
    $response->assertJsonFragment(['name' => 'New Product', 'price' => 100]);

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
    $response->assertJsonFragment(['name' => 'Test Product', "price" => 100]);
});

it('can update a product', function () {
    $this->actingAs($this->user);
    // Define new data for the update
    $image = UploadedFile::fake()->image('product.jpg');

    $newData = [
        'name' => 'Updated Product Name',
        'description' => 'Updated description.',
        "image" => $image,
        "is_active" => true,
        "prices" => [
            ["type" => \App\Enums\UserTypes::Normal->name, "price" => 100],
            ["type" => \App\Enums\UserTypes::Silver->name, "price" => 80],
            ["type" => \App\Enums\UserTypes::Gold->name, "price" => 60]
        ]
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


it('only returns active products via the API', function () {
    $this->actingAs($this->user);

    // Arrange: create active and inactive products
    $activeProduct = Product::factory()->active()->create();
    $inactiveProduct = Product::factory()->active(false)->create();

    // Act: Make a request to the API to fetch only active products
    $response = $this->getJson('/api/v1/products');

    // Assert: The active product is returned, but the inactive one is not
    $response->assertStatus(200)
        ->assertJsonFragment(['id' => $activeProduct->id])
        ->assertJsonMissing(['id' => $inactiveProduct->id]);
});

it('return silver type price for gold user', function () {
    $this->actingAs($this->silverUser);
    // Send a POST request to create a new product
    $response = $this->getJson('/api/v1/products/' . $this->product->id);
    // Assert that the product was created successfully
    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Test Product', 'price' => 80])
        ->assertJsonMissing(['price' =>100]);
});

it('return gold type price for gold user', function () {
    $this->actingAs($this->goldUser);
    // Send a POST request to create a new product
    $response = $this->getJson('/api/v1/products/' . $this->product->id);
    // Assert that the product was created successfully
    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Test Product', 'price' => 60])
        ->assertJsonMissing(['price' =>100]);
});

