<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Notification\UserFollowerAlert;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification\UserFollowerAlert>
 */
class UserFollowerAlertFactory extends Factory
{
    protected $model = UserFollowerAlert::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->numberBetween(1, 100),
            'total' => $this->faker->numberBetween(1, 100),
            'used' => $this->faker->numberBetween(0, 50),
            'begin_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'end_at' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
        ];
    }
}
