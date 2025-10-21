<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\AffiliateInvite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\AffiliateInvite>
 */
class AffiliateInviteFactory extends Factory
{
    protected $model = AffiliateInvite::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->numberBetween(1, 100),
            'email' => $this->faker->safeEmail(),
            'phone_number' => $this->faker->optional()->phoneNumber(),
            'token' => $this->faker->uuid(),
            'params' => $this->faker->optional()->randomElement(['key1' => 'value1', 'key2' => 'value2']),
        ];
    }
}
