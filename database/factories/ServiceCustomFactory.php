<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\ServiceCustom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\ServiceCustom>
 */
class ServiceCustomFactory extends Factory
{
    protected $model = ServiceCustom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone_price' => $this->faker->randomFloat(2, 0.01, 100),
            'feed_price' => $this->faker->randomFloat(2, 0.01, 50),
            'phone_min' => $this->faker->numberBetween(1, 10),
            'phone_max' => $this->faker->numberBetween(10, 100),
            'feed_min' => $this->faker->numberBetween(1, 5),
            'feed_max' => $this->faker->numberBetween(5, 50),
        ];
    }
}
