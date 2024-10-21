<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$password = 'password123';

it('allows a user to login with valid credentials via API', function () use ($password) {

    $user = User::factory()->create([
        'password' => Hash::make($password),
    ]);

    $response = $this->postJson('/api/v1/login', [
        'username' => $user->username,
        'password' => $password,
    ]);
//    $response->dump();

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'user',    // Asserts that the user data exists
                'token',   // Asserts that the token exists
            ],
        ])
        ->assertJson(['status' => 'success', 'message' => 'Successfully logged In']);
    expect($response->json('data.token'))->toBeString()->not->toBeEmpty();
});

it('prevents login with invalid credentials via API', function () use ($password) {
    $user = User::factory()->create([
        'password' => Hash::make($password),
    ]);

    $response = $this->postJson('/api/v1/login', [
        'username' => $user->username,
        'password' => 'wrong password',
    ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Invalid Credentials']);

    $this->assertGuest();
});


it('allows a user to register via API', function () use ($password) {
    // Data for the new user registration
    $userData = [
        'name' => 'John',
        'username' => 'john',
        'password' => $password,
        'password_confirmation' => $password, // Password confirmation
    ];

    // Send a POST request to the API registration route
    $response = $this->postJson('/api/v1/register', $userData);

    // Assert the response status is 200 (Created)
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'user',    // The user data should exist
                'token',   // A token should be returned
            ],
        ]);

    expect($response->json('data.token'))->toBeString()->not->toBeEmpty();
    $this->assertDatabaseHas('users', [
        'name' => 'John',
        'username' => 'john',
    ]);
});


it('prevents registration with missing or invalid data via API', function () {
    // Send a POST request to the API registration route with invalid data
    $response = $this->postJson('/api/v1/register', [
        'name' => '',
        'username' => 'short',
        'password' => 'short',
        'password_confirmation' => 'does-not-match',
    ]);

    // Assert the response status is 422 (Unprocessable Entity) due to validation errors
    $response->assertStatus(422)
        ->assertJsonStructure([
            'errors' => [
                'name',
                'password',
            ],
        ]);

    // Assert that the user was not created in the database
    $this->assertDatabaseMissing('users', [
        'username' => 'short',
    ]);
});


