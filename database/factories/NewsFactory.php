<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        
        return [
            'name' => $title,
            'slug' => Str::slug($title),
            'image' => fake()->boolean(70) ? fake()->imageUrl(800, 600, 'news') : null,
            'status' => fake()->numberBetween(0, 2), // 0=draft, 1=published, 2=archived
            'params' => json_encode([
                'category' => fake()->randomElement(['technology', 'business', 'politics', 'sports', 'entertainment']),
                'priority' => fake()->numberBetween(1, 5),
                'tags' => fake()->words(3),
                'author' => fake()->name(),
                'source' => fake()->company(),
                'read_time' => fake()->numberBetween(2, 15) . ' min read'
            ]),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the news is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 1,
        ]);
    }

    /**
     * Indicate that the news is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 0,
        ]);
    }

    /**
     * Indicate that the news is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 2,
        ]);
    }

    /**
     * Indicate that the news has an image.
     */
    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image' => fake()->imageUrl(800, 600, 'news'),
        ]);
    }
}