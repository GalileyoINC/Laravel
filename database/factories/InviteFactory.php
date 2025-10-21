<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\Invite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\Invite>
 */
class InviteFactory extends Factory
{
    protected $model = Invite::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->numberBetween(1, 100),
            'id_follower_list' => $this->faker->numberBetween(1, 40),
            'email' => $this->faker->safeEmail(),
            'name' => $this->faker->optional()->name(),
            'phone_number' => $this->faker->optional()->phoneNumber(),
            'token' => $this->faker->uuid(),
        ];
    }
}
