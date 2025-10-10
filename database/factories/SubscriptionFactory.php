<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
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
            'id_subscription_category' => fake()->numberBetween(1, 10),
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
                'tags' => fake()->words(3)
            ]),
            'is_custom' => fake()->boolean(30), // 30% chance
            'id_influencer' => fake()->boolean(40) ? fake()->numberBetween(1, 50) : null,
            'is_public' => fake()->boolean(70), // 70% chance
            'type' => fake()->randomElement(['regular', 'satellite', 'marketstack']),
            'sub_type' => fake()->randomElement(['indx', 'ticker', 'news']),
            'symbol' => fake()->boolean(20) ? fake()->lexify('????') : null,
            'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
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
     * Indicate that the subscription is for marketstack.
     */
    public function marketstack(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'marketstack',
            'sub_type' => fake()->randomElement(['indx', 'ticker']),
            'symbol' => fake()->lexify('????'),
        ]);
    }
}