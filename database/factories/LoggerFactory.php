<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Analytics\Logger;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Analytics\Logger>
 */
class LoggerFactory extends Factory
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
            'employee_login' => $this->faker->userName(),
            'employee_first_name' => $this->faker->firstName(),
            'employee_last_name' => $this->faker->lastName(),
            'access_level' => $this->faker->numberBetween(1, 5),
            'category' => $this->faker->randomElement(['system', 'user', 'admin', 'api', 'database']),
            'error_type' => $this->faker->randomElement(['Error', 'Warning', 'Info']),
            'source' => $this->faker->word(),
            'content' => $this->faker->sentence(),
            'module' => $this->faker->randomElement(['auth', 'user', 'payment', 'subscription']),
            'controller' => $this->faker->word().'Controller',
            'action' => $this->faker->randomElement(['index', 'show', 'store', 'update', 'destroy']),
            'ip' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'primary_json' => json_encode(['id' => $this->faker->numberBetween(1, 1000)]),
            'changed_fields' => json_encode(['field' => 'value']),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create an error log.
     */
    public function error(): static
    {
        return $this->state(fn (array $attributes) => [
            'error_type' => 'Error',
            'content' => 'An error occurred: '.$this->faker->sentence(),
        ]);
    }

    /**
     * Create an info log.
     */
    public function info(): static
    {
        return $this->state(fn (array $attributes) => [
            'error_type' => 'Info',
            'content' => 'User action: '.$this->faker->sentence(),
        ]);
    }
}
