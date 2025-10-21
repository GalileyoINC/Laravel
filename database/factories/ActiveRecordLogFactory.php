<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\System\ActiveRecordLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System\ActiveRecordLog>
 */
class ActiveRecordLogFactory extends Factory
{
    protected $model = ActiveRecordLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->optional()->numberBetween(1, 100),
            'id_staff' => $this->faker->optional()->numberBetween(1, 20),
            'action_type' => $this->faker->numberBetween(1, 3), // Create, Update, Delete
            'model' => $this->faker->randomElement(['User', 'Invoice', 'Subscription', 'Staff']),
            'id_model' => $this->faker->numberBetween(1, 100),
            'field' => $this->faker->optional()->randomElement(['name', 'email', 'status', 'price']),
            'changes' => $this->faker->optional()->randomElement(['old_value' => 'old', 'new_value' => 'new']),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
