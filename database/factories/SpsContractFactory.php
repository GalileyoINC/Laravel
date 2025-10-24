<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\SpsContract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\SpsContract>
 */
class SpsContractFactory extends Factory
{
    protected $model = SpsContract::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->numberBetween(1, 100), // Use existing user IDs
            'id_contract' => $this->faker->unique()->numberBetween(1000, 9999),
            'id_plan' => $this->faker->optional()->numberBetween(1, 30), // Use existing user plan IDs
            'id_service' => $this->faker->numberBetween(1, 25),
            'alert' => $this->faker->numberBetween(1, 10),
            'max_phone_cnt' => $this->faker->numberBetween(1, 10),
            'pay_interval' => $this->faker->randomElement([1, 12]), // Monthly or Annual
            'begin_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'ended_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'terminated_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'is_secondary' => $this->faker->boolean(20), // 20% secondary
            'user_plan_data' => $this->faker->optional()->randomElements(['key1' => 'value1', 'key2' => 'value2']),
        ];
    }
}
