<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\SmsPoolReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\SmsPoolReport>
 */
class SmsPoolReportFactory extends Factory
{
    protected $model = SmsPoolReport::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'influencer_min' => $this->faker->optional()->numberBetween(1, 10),
            'influencer_max' => $this->faker->optional()->numberBetween(10, 100),
            'influencer_avg' => $this->faker->optional()->randomFloat(2, 1, 50),
            'influencer_median' => $this->faker->optional()->randomFloat(2, 1, 50),
            'influencer_total' => $this->faker->optional()->numberBetween(100, 1000),
            'influencer_users' => $this->faker->optional()->numberBetween(10, 100),
            'api_min' => $this->faker->optional()->numberBetween(1, 10),
            'api_max' => $this->faker->optional()->numberBetween(10, 100),
            'api_avg' => $this->faker->optional()->randomFloat(2, 1, 50),
            'api_median' => $this->faker->optional()->randomFloat(2, 1, 50),
            'api_total' => $this->faker->optional()->numberBetween(100, 1000),
            'api_users' => $this->faker->optional()->numberBetween(10, 100),
            'day' => $this->faker->date(),
        ];
    }
}
