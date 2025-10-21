<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\AuthRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\AuthRule>
 */
class AuthRuleFactory extends Factory
{
    protected $model = AuthRule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . '_rule_' . $this->faker->randomNumber(3),
            'data' => $this->faker->optional()->randomElement(['key1' => 'value1', 'key2' => 'value2']),
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
}
