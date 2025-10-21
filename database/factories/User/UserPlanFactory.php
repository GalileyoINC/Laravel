<?php

declare(strict_types=1);

namespace Database\Factories\User;

use App\Models\User\UserPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\UserPlan>
 */
class UserPlanFactory extends Factory
{
    protected $model = UserPlan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->numberBetween(1, 100),
            'id_service' => $this->faker->numberBetween(1, 25),
            'id_invoice_line' => $this->faker->optional()->numberBetween(1, 50),
            'is_primary' => $this->faker->boolean(),
            'alert' => $this->faker->optional()->numberBetween(1, 10),
            'max_phone_cnt' => $this->faker->optional()->numberBetween(1, 5),
            'pay_interval' => $this->faker->randomElement([1, 12]), // Monthly or Annual
            'price_before_prorate' => $this->faker->optional()->randomFloat(2, 10, 100),
            'price_after_prorate' => $this->faker->optional()->randomFloat(2, 10, 100),
            'settings' => $this->faker->optional()->randomElement(['key1' => 'value1', 'key2' => 'value2']),
            'begin_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'end_at' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'devices' => $this->faker->numberBetween(1, 10),
            'is_new_custom' => $this->faker->boolean(),
            'is_not_receive_message' => $this->faker->boolean(),
        ];
    }
}
