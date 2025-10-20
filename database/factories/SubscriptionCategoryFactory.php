<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Subscription\SubscriptionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription\SubscriptionCategory>
 */
class SubscriptionCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubscriptionCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'id_parent' => null, // Top-level category
            'position_no' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the category is a subcategory.
     */
    public function subcategory(): static
    {
        return $this->state(fn (array $attributes) => [
            'id_parent' => SubscriptionCategory::factory(),
        ]);
    }

    /**
     * Indicate that the category is a top-level category.
     */
    public function topLevel(): static
    {
        return $this->state(fn (array $attributes) => [
            'id_parent' => null,
        ]);
    }
}
