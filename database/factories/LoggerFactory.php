<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Analytics\Logger;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Analytics\Logger>
 */
class AnalyticsLoggerFactory extends Factory
{
    protected $model = Logger::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'level' => $this->faker->randomElement(['debug', 'info', 'warning', 'error', 'critical']),
            'message' => $this->faker->sentence(),
            'context' => json_encode([
                'user_id' => $this->faker->numberBetween(1, 100),
                'ip' => $this->faker->ipv4(),
                'user_agent' => $this->faker->userAgent(),
            ]),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create an error log.
     */
    public function error(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'error',
            'message' => 'An error occurred: '.$this->faker->sentence(),
        ]);
    }

    /**
     * Create an info log.
     */
    public function info(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'info',
            'message' => 'User action: '.$this->faker->sentence(),
        ]);
    }
}
