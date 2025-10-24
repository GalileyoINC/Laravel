<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\Promocode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\Promocode>
 */
class PromocodeFactory extends Factory
{
    protected $model = Promocode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->numberBetween(1, 3),
            'text' => strtoupper($this->faker->unique()->lexify('??????')),
            'discount' => $this->faker->numberBetween(5, 50),
            'is_active' => $this->faker->boolean(80), // 80% active
            'active_from' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'active_to' => $this->faker->dateTimeBetween('now', '+1 year'),
            'trial_period' => $this->faker->optional()->numberBetween(7, 30),
            'show_on_frontend' => $this->faker->boolean(30), // 30% show on frontend
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
