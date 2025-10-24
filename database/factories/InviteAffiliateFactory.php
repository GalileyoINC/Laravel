<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\InviteAffiliate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\InviteAffiliate>
 */
class InviteAffiliateFactory extends Factory
{
    protected $model = InviteAffiliate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_inviter' => $this->faker->numberBetween(1, 100),
            'id_invited' => $this->faker->numberBetween(1, 100),
            'id_invite_invoice' => $this->faker->numberBetween(1, 50), // Use existing invoice IDs from DemoDataSeeder
            'id_reward_invoice' => $this->faker->optional()->numberBetween(1, 50),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
        ];
    }
}
