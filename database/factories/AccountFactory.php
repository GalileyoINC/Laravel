<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\System\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System\Account>
 */
class AccountFactory extends Factory
{
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'accountId' => $this->faker->uuid(),
            'providerId' => $this->faker->randomElement(['google', 'facebook', 'apple', 'twitter']),
            'userId' => $this->faker->numberBetween(1, 100),
            'accessToken' => $this->faker->optional()->sha256(),
            'refreshToken' => $this->faker->optional()->sha256(),
            'idToken' => $this->faker->optional()->sha256(),
            'accessTokenExpiresAt' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'refreshTokenExpiresAt' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'scope' => $this->faker->optional()->words(3, true),
            'password' => $this->faker->optional()->password(),
            'createdAt' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updatedAt' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
