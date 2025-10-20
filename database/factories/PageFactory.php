<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Content\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content\Page>
 */
class ContentPageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(3);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'status' => $this->faker->numberBetween(0, 1),
            'params' => [
                'meta_title' => $this->faker->sentence(5),
                'meta_description' => $this->faker->paragraph(2),
                'template' => $this->faker->randomElement(['default', 'landing', 'blog']),
            ],
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the page is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 1,
        ]);
    }

    /**
     * Indicate that the page is draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 0,
        ]);
    }

    /**
     * Create a landing page.
     */
    public function landing(): static
    {
        return $this->state(fn (array $attributes) => [
            'params' => array_merge($attributes['params'] ?? [], [
                'template' => 'landing',
                'show_header' => false,
                'show_footer' => false,
            ]),
        ]);
    }
}
