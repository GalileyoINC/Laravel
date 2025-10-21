<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\UserPointHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\UserPointHistory>
 */
class UserPointHistoryFactory extends Factory
{
    protected $model = UserPointHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->numberBetween(1, 100),
            'id_user_point_settings' => $this->faker->numberBetween(1, 10), // Use existing IDs from UserPointSetting
            'id_sms_pool' => $this->faker->optional()->numberBetween(1, 50),
            'id_comment' => $this->faker->optional()->numberBetween(1, 50),
            'quantity' => $this->faker->numberBetween(1, 100),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
        ];
    }
}
