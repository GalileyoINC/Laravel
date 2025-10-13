<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->numberBetween(1, 5),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'bonus_point' => $this->faker->numberBetween(0, 100),
            'settings' => [
                'duration' => $this->faker->numberBetween(1, 12),
                'features' => $this->faker->words(3),
            ],
            'is_active' => $this->faker->boolean(80),
            'compensation' => [
                'type' => 'percentage',
                'value' => $this->faker->numberBetween(5, 20),
            ],
            'fee' => $this->faker->randomFloat(2, 0, 50),
            'fee_annual' => $this->faker->randomFloat(2, 0, 200),
            'termination_fee' => $this->faker->randomFloat(2, 0, 100),
            'termination_period' => $this->faker->numberBetween(1, 30),
            'special_price' => $this->faker->optional()->randomFloat(2, 5, 500),
            'is_special_price' => $this->faker->boolean(20),
            'special_fee' => $this->faker->optional()->randomFloat(2, 0, 25),
            'is_special_fee' => $this->faker->boolean(15),
            'special_fee_annual' => $this->faker->optional()->randomFloat(2, 0, 100),
            'is_special_fee_annual' => $this->faker->boolean(10),
            'special_datetime' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
        ];
    }

    /**
     * Indicate that the service is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the service is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the service has special pricing.
     */
    public function withSpecialPrice(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_special_price' => true,
            'special_price' => $this->faker->randomFloat(2, 5, 500),
            'special_datetime' => $this->faker->dateTimeBetween('now', '+1 year'),
        ]);
    }
}
