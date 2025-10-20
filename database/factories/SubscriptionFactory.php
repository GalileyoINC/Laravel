<?php

declare(strict_types=1);

namespace Database\Factories\Subscription;

use App\Models\Subscription\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_subscription_category' => \App\Models\Subscription\SubscriptionCategory::factory(),
            'name' => fake()->words(2, true),
            'title' => fake()->sentence(3),
            'position_no' => fake()->numberBetween(1, 100),
            'description' => fake()->paragraph(2),
            'rule' => fake()->sentence(),
            'alert_type' => fake()->numberBetween(1, 3),
            'is_active' => fake()->boolean(80), // 80% chance
            'is_hidden' => fake()->boolean(20), // 20% chance
            'percent' => fake()->randomFloat(2, 0, 100),
            'sended_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'params' => json_encode([
                'frequency' => fake()->randomElement(['daily', 'weekly', 'monthly']),
                'priority' => fake()->numberBetween(1, 5),
                'tags' => fake()->words(3),
            ]),
            'is_custom' => fake()->boolean(30), // 30% chance
            'id_influencer' => null,
            'price' => fake()->randomFloat(2, 0, 100),
            'bonus_point' => fake()->numberBetween(0, 1000),
            'token' => fake()->boolean(60) ? fake()->uuid() : null,
            'ipfs_id' => fake()->boolean(20) ? fake()->uuid() : null,
            'is_public' => fake()->boolean(70), // 70% chance
            'is_fake' => fake()->boolean(10), // 10% chance
            'type' => fake()->numberBetween(1, 3),
            'show_reactions' => fake()->boolean(80), // 80% chance
            'show_comments' => fake()->boolean(70), // 70% chance
        ];
    }

    /**
     * Indicate that the subscription is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'is_hidden' => false,
        ]);
    }

    /**
     * Indicate that the subscription is public.
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the subscription is custom.
     */
    public function custom(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_custom' => true,
            'is_public' => false,
        ]);
    }

    /**
     * Indicate that the subscription is premium.
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => fake()->randomFloat(2, 10, 50),
            'bonus_point' => fake()->numberBetween(100, 500),
            'is_public' => true,
        ]);
    }
}
