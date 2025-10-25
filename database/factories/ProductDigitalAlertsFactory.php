<?php

namespace Database\Factories;

use App\Models\ProductDigitalAlerts;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductDigitalAlerts>
 */
class ProductDigitalAlertsFactory extends Factory
{
    protected $model = ProductDigitalAlerts::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $severities = ['critical', 'high', 'medium', 'low'];
        $categories = ['emergency', 'weather', 'security', 'traffic', 'medical', 'fire', 'police'];
        $statuses = ['active', 'inactive', 'expired'];
        $types = ['weather', 'traffic', 'security', 'medical', 'fire', 'police'];

        return [
            'type' => $this->faker->randomElement($types),
            'status' => $this->faker->randomElement($statuses),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'alert_data' => json_encode([
                'source' => $this->faker->company,
                'confidence' => $this->faker->numberBetween(1, 100),
                'affected_area' => $this->faker->city,
            ]),
            'latitude' => $this->faker->latitude(40.5, 41.0), // NYC area
            'longitude' => $this->faker->longitude(-74.5, -73.5), // NYC area
            'address' => $this->faker->address,
            'severity' => $this->faker->randomElement($severities),
            'category' => $this->faker->randomElement($categories),
            'affected_radius' => $this->faker->numberBetween(100, 5000),
            'source' => $this->faker->company,
            'expires_at' => $this->faker->optional(0.7)->dateTimeBetween('now', '+1 week'),
        ];
    }

    /**
     * Indicate that the alert is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the alert is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the alert is critical severity.
     */
    public function critical(): static
    {
        return $this->state(fn (array $attributes) => [
            'severity' => 'critical',
        ]);
    }

    /**
     * Indicate that the alert is high severity.
     */
    public function high(): static
    {
        return $this->state(fn (array $attributes) => [
            'severity' => 'high',
        ]);
    }

    /**
     * Indicate that the alert is weather category.
     */
    public function weather(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'weather',
            'type' => 'weather',
        ]);
    }

    /**
     * Indicate that the alert is traffic category.
     */
    public function traffic(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'traffic',
            'type' => 'traffic',
        ]);
    }
}