<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\Provider>
 */
class ProviderFactory extends Factory
{
    protected $model = Provider::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->companyEmail(),
            'is_satellite' => $this->faker->boolean(30),
            'country' => $this->faker->countryCode(),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }

    /**
     * Indicate that the provider is a satellite provider.
     */
    public function satellite(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_satellite' => true,
        ]);
    }

    /**
     * Indicate that the provider is not a satellite provider.
     */
    public function terrestrial(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_satellite' => false,
        ]);
    }
}
