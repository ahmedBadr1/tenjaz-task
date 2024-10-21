<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a sample user
    $this->user = User::factory()->create([
        'name' => 'Admin User',
        'username' => 'admin',
        'password' => Hash::make('password'),
    ]);
});

it('can create a user', function () {
    $this->actingAs($this->user);

    // Define the data for a new user
    $userData = [
        'name' => 'Test User',
        'username' => 'test',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    // Send a POST request to create a new user
    $response = $this->post('/api/v1/users', $userData);

    // Assert that the user was created successfully
    $response->assertStatus(201);
    $response->assertJsonFragment(['username' => 'test']);

    // Assert that the user exists in the database
    $this->assertDatabaseHas('users', ['username' => 'test']);
});

it('can get all users', function () {
    $this->actingAs($this->user);
    // Send a GET request to retrieve all users
    $response = $this->getJson('/api/v1/users');

    // Assert that the request was successful and that the data is correct
    $response->assertStatus(200);
    $response->assertJsonFragment(['username' => 'admin']);
});

it('can get a specific user by id', function () {
    $this->actingAs($this->user);

    // Send a GET request to retrieve the specific user
    $response = $this->getJson('/api/v1/users/' . $this->user->id);

    // Assert that the request was successful and that the data is correct
    $response->assertStatus(200);
    $response->assertJsonFragment(['username' => 'admin']);
});

it('can update a user', function () {
    $this->actingAs($this->user);
    // Define new data for the update
    $newData = [
        'name' => 'Updated Name',
        'username' => 'Updated'
    ];

    // Send a PUT request to update the user
    $response = $this->putJson('/api/v1/users/' . $this->user->id, $newData);

    // Assert that the request was successful
    $response->assertStatus(200);
    $response->assertJsonFragment(['username' => 'Updated']);

    // Assert that the user was updated in the database
    $this->assertDatabaseHas('users', ['username' => 'Updated']);
});

it('can delete a user', function () {
    $this->actingAs($this->user);
    // Send a DELETE request to remove the user
    $response = $this->deleteJson('/api/v1/users/' . $this->user->id);

    // Assert that the request was successful
    $response->assertStatus(200);
    $response->assertJsonFragment(['message' => 'User deleted successfully']);

    // Assert that the user is no longer in the database
    $this->assertDatabaseMissing('users', ['username' => 'test']);
});
