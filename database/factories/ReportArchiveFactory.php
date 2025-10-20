<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Analytics\ReportArchive;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Analytics\ReportArchive>
 */
class ReportArchiveFactory extends Factory
{
    protected $model = ReportArchive::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'report_type' => $this->faker->randomElement(['monthly', 'quarterly', 'yearly', 'custom']),
            'period_start' => $this->faker->dateTimeBetween('-2 years', '-1 year'),
            'period_end' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'data' => json_encode([
                'total_users' => $this->faker->numberBetween(100, 10000),
                'active_users' => $this->faker->numberBetween(50, 5000),
                'revenue' => $this->faker->randomFloat(2, 1000, 100000),
                'metrics' => [
                    'page_views' => $this->faker->numberBetween(1000, 100000),
                    'sessions' => $this->faker->numberBetween(500, 50000),
                ],
            ]),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create a monthly report.
     */
    public function monthly(): static
    {
        return $this->state(fn (array $attributes) => [
            'report_type' => 'monthly',
        ]);
    }

    /**
     * Create a yearly report.
     */
    public function yearly(): static
    {
        return $this->state(fn (array $attributes) => [
            'report_type' => 'yearly',
        ]);
    }
}
