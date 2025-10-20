<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Analytics\ReportReferral;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Analytics\ReportReferral>
 */
class ReportReferralFactory extends Factory
{
    protected $model = ReportReferral::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'referral_code' => $this->faker->unique()->regexify('[A-Z0-9]{8}'),
            'referral_count' => $this->faker->numberBetween(0, 50),
            'total_earnings' => $this->faker->randomFloat(2, 0, 1000),
            'status' => $this->faker->randomElement(['active', 'inactive', 'suspended']),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create an active referral.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Create a high-earning referral.
     */
    public function highEarning(): static
    {
        return $this->state(fn (array $attributes) => [
            'total_earnings' => $this->faker->randomFloat(2, 500, 1000),
            'referral_count' => $this->faker->numberBetween(20, 50),
        ]);
    }
}
