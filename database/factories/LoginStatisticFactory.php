<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Analytics\LoginStatistic;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Analytics\LoginStatistic>
 */
class LoginStatisticFactory extends Factory
{
    protected $model = LoginStatistic::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'id_device' => null,
            'type' => $this->faker->numberBetween(0, 2), // 0=web, 1=mobile, 2=api
            'ip' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'data' => json_encode(['success' => $this->faker->boolean(90)]),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create a successful login.
     */
    public function successful(): static
    {
        return $this->state(fn (array $attributes) => [
            'data' => json_encode(['success' => true]),
        ]);
    }

    /**
     * Create a failed login.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'data' => json_encode(['success' => false]),
        ]);
    }
}
