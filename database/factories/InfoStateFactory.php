<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Analytics\InfoState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Analytics\InfoState>
 */
class InfoStateFactory extends Factory
{
    protected $model = InfoState::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->word() . '_' . $this->faker->randomNumber(3),
            'value' => json_encode([
                'state' => $this->faker->randomElement(['active', 'inactive', 'pending', 'suspended']),
                'description' => $this->faker->sentence(),
                'last_updated' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
                'source' => $this->faker->randomElement(['api', 'web', 'mobile', 'system']),
            ]),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create an active state.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => 'active',
        ]);
    }

    /**
     * Create an inactive state.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => 'inactive',
        ]);
    }
}
