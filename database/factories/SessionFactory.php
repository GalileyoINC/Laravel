<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\Session;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\Session>
 */
class SessionFactory extends Factory
{
    protected $model = Session::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'expiresAt' => $this->faker->dateTimeBetween('now', '+1 year'),
            'token' => $this->faker->uuid(),
            'createdAt' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updatedAt' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'ipAddress' => $this->faker->optional()->ipv4(),
            'userAgent' => $this->faker->optional()->userAgent(),
            'userId' => $this->faker->numberBetween(1, 100), // Remove optional() since it cannot be null
        ];
    }
}
