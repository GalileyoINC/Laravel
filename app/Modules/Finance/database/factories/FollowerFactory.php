<?php

declare(strict_types=1);

namespace App\Modules\Finance\database\factories;

use App\Models\Subscription\Follower;
use App\Models\Subscription\FollowerList;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription\Follower>
 */
class FollowerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Follower::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_follower_list' => FollowerList::factory(),
            'id_user_leader' => User::factory(),
            'id_user_follower' => User::factory(),
            'is_active' => fake()->boolean(90), // 90% chance
            'invite_settings' => json_encode([
                'notifications' => fake()->boolean(70),
                'email_updates' => fake()->boolean(60),
                'sms_updates' => fake()->boolean(40),
                'frequency' => fake()->randomElement(['daily', 'weekly', 'monthly']),
            ]),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the follower is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the follower is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the follower is recent.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }
}
