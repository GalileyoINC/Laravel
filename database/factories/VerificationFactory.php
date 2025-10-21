<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\Verification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\Verification>
 */
class VerificationFactory extends Factory
{
    protected $model = Verification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'identifier' => $this->faker->uuid(),
            'value' => $this->faker->randomNumber(6),
            'expiresAt' => $this->faker->dateTimeBetween('now', '+1 year'),
            'createdAt' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updatedAt' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
