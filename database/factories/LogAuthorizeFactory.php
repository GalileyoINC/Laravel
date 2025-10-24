<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Analytics\LogAuthorize;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Analytics\LogAuthorize>
 */
class LogAuthorizeFactory extends Factory
{
    protected $model = LogAuthorize::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->numberBetween(1, 100), // Use existing user IDs
            'id_money_transaction' => $this->faker->optional()->numberBetween(1, 50), // Use existing money transaction IDs
            'name' => $this->faker->optional()->randomElement(['login', 'logout', 'register', 'password_reset', 'email_verify']),
            'request' => $this->faker->optional()->randomElements(['ip' => $this->faker->ipv4(), 'user_agent' => $this->faker->userAgent()]),
            'response' => $this->faker->optional()->randomElements(['success' => $this->faker->boolean(85), 'message' => $this->faker->sentence()]),
            'status' => $this->faker->optional()->numberBetween(0, 2),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create a successful authorization.
     */
    public function successful(): static
    {
        return $this->state(fn (array $attributes) => [
            'success' => true,
        ]);
    }

    /**
     * Create a failed authorization.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'success' => false,
        ]);
    }
}
