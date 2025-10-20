<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\Bundle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\Bundle>
 */
class BundleFactory extends Factory
{
    protected $model = Bundle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'type' => $this->faker->numberBetween(1, 3),
            'pay_interval' => $this->faker->numberBetween(1, 12),
            'is_active' => $this->faker->boolean(80),
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the bundle is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the bundle is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a monthly bundle.
     */
    public function monthly(): static
    {
        return $this->state(fn (array $attributes) => [
            'pay_interval' => 1,
        ]);
    }

    /**
     * Create a yearly bundle.
     */
    public function yearly(): static
    {
        return $this->state(fn (array $attributes) => [
            'pay_interval' => 12,
        ]);
    }
}
