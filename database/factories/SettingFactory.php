<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\System\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System\Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->regexify('[a-z]{2,8}'),
            'prod' => $this->faker->sentence(),
            'dev' => $this->faker->sentence(),
        ];
    }

    /**
     * Create a boolean setting.
     */
    public function boolean(): static
    {
        return $this->state(fn (array $attributes) => [
            'prod' => $this->faker->boolean() ? '1' : '0',
            'dev' => $this->faker->boolean() ? '1' : '0',
        ]);
    }

    /**
     * Create a numeric setting.
     */
    public function numeric(): static
    {
        return $this->state(fn (array $attributes) => [
            'prod' => (string) $this->faker->numberBetween(1, 1000),
            'dev' => (string) $this->faker->numberBetween(1, 1000),
        ]);
    }
}
