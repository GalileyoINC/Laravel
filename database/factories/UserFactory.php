<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password_hash' => Hash::make('password'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'role' => fake()->numberBetween(1, 3),
            'is_valid_email' => fake()->boolean(80), // 80% chance of being valid
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create an influencer user.
     */
    public function influencer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 2, // Influencer role
            'is_valid_email' => true,
        ]);
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 1, // Admin role
            'is_valid_email' => true,
        ]);
    }
}