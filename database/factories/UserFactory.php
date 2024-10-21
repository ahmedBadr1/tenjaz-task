<?php

namespace Database\Factories;

use App\Enums\UserTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->firstName(),
            'avatar' => fake()->imageUrl(),
            'password' => static::$password ??= Hash::make('password'),
            'is_active' => fake()->boolean(),
        ];
    }

    /**
     * Indicate that the model is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * generate normal user .
     */
    public function normal(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => UserTypes::Normal->value,
        ]);
    }
    /**
     * generate silver user .
     */
    public function silver(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => UserTypes::Silver->value,
        ]);
    }
    /**
     * generate gold user .
     */
    public function gold(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => UserTypes::Gold->value,
        ]);
    }
}
