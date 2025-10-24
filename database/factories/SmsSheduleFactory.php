<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\SmsShedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\SmsShedule>
 */
class SmsSheduleFactory extends Factory
{
    protected $model = SmsShedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->optional()->numberBetween(1, 100),
            'id_staff' => $this->faker->numberBetween(1, 15), // Use existing staff IDs from DemoDataSeeder
            'id_subscription' => $this->faker->optional()->numberBetween(1, 30),
            'id_follower_list' => $this->faker->optional()->numberBetween(1, 40),
            'id_sms_pool' => $this->faker->optional()->numberBetween(1, 150),
            'purpose' => $this->faker->optional()->numberBetween(1, 10),
            'status' => $this->faker->optional()->numberBetween(0, 4),
            'body' => $this->faker->sentence(10),
            'sended_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'id_assistant' => $this->faker->optional()->numberBetween(1, 20),
            'short_body' => $this->faker->optional()->sentence(5),
            'url' => $this->faker->optional()->url(),
        ];
    }

    /**
     * Create a scheduled SMS.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 week'),
        ]);
    }

    /**
     * Create a sent SMS.
     */
    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sent',
            'sent_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }
}
