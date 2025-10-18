<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Analytics\Report;
use App\Models\Content\News;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Analytics\Report>
 */
class AnalyticsReportFactory extends Factory
{
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_news' => News::factory(),
            'id_user' => User::factory(),
            'reason' => $this->faker->randomElement(['spam', 'inappropriate', 'harassment', 'fake_news', 'other']),
            'additional_text' => $this->faker->optional(0.7)->paragraph(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create a spam report.
     */
    public function spam(): static
    {
        return $this->state(fn (array $attributes) => [
            'reason' => 'spam',
        ]);
    }

    /**
     * Create an inappropriate content report.
     */
    public function inappropriate(): static
    {
        return $this->state(fn (array $attributes) => [
            'reason' => 'inappropriate',
        ]);
    }
}
