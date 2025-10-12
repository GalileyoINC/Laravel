<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Subscription\InfluencerPage;
use App\Models\Subscription\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription\InfluencerPage>
 */
class InfluencerPageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InfluencerPage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'id_subscription' => Subscription::factory(),
            'title' => $title,
            'alias' => Str::slug($title),
            'description' => fake()->paragraph(3),
            'image' => fake()->boolean(70) ? fake()->word().'.jpg' : null,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the influencer page has an image.
     */
    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image' => fake()->word().'.jpg',
        ]);
    }

    /**
     * Indicate that the influencer page is recent.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Indicate that the influencer page is old.
     */
    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => fake()->dateTimeBetween('-2 years', '-1 year'),
            'updated_at' => fake()->dateTimeBetween('-1 year', '-6 months'),
        ]);
    }
}
