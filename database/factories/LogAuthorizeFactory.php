<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Analytics\LogAuthorize;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Analytics\LogAuthorize>
 */
class AnalyticsLogAuthorizeFactory extends Factory
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
            'id_user' => User::factory(),
            'action' => $this->faker->randomElement(['login', 'logout', 'register', 'password_reset', 'email_verify']),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'success' => $this->faker->boolean(85),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
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
