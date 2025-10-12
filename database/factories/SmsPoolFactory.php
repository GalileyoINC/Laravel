<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\SmsPool;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\SmsPool>
 */
class SmsPoolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmsPool::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'id_staff' => null,
            'id_subscription' => Subscription::factory(),
            'id_follower_list' => null,
            'purpose' => fake()->numberBetween(1, 5), // 1=news, 2=alert, 3=promotion, 4=update, 5=other
            'status' => fake()->numberBetween(0, 3), // 0=pending, 1=sent, 2=failed, 3=cancelled
            'body' => fake()->paragraph(3),
            'sms_provider' => fake()->randomElement(['twilio', 'aws', 'nexmo', 'messagebird']),
            'id_assistant' => null,
            'short_body' => fake()->sentence(2),
            'url' => fake()->boolean(30) ? fake()->url() : null,
            'is_ban' => false,
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }

    /**
     * Indicate that the SMS is sent.
     */
    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 1,
        ]);
    }

    /**
     * Indicate that the SMS is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 0,
        ]);
    }

    /**
     * Indicate that the SMS failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 2,
        ]);
    }

    /**
     * Indicate that the SMS is scheduled.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 0, // pending
        ]);
    }

    /**
     * Indicate that the SMS is for news.
     */
    public function news(): static
    {
        return $this->state(fn (array $attributes) => [
            'purpose' => 1,
            'body' => fake()->paragraph(2),
            'short_body' => fake()->sentence(1),
        ]);
    }

    /**
     * Indicate that the SMS is an alert.
     */
    public function alert(): static
    {
        return $this->state(fn (array $attributes) => [
            'purpose' => 2,
            'body' => fake()->sentence(3),
            'short_body' => fake()->sentence(1),
        ]);
    }

    /**
     * Indicate that the SMS is a promotion.
     */
    public function promotion(): static
    {
        return $this->state(fn (array $attributes) => [
            'purpose' => 3,
            'body' => fake()->paragraph(2),
            'short_body' => fake()->sentence(1),
            'url' => fake()->url(),
        ]);
    }
}
