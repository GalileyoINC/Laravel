<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'auth_key' => Str::random(32),
            'password_hash' => static::$password ??= Hash::make('password'),
            'password_reset_token' => null,
            'role' => fake()->numberBetween(1, 3),
            'status' => fake()->numberBetween(0, 1),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone_profile' => fake()->phoneNumber(),
            'country' => fake()->countryCode(),
            'state' => fake()->stateAbbr(),
            'is_influencer' => fake()->boolean(20), // 20% chance
            'zip' => fake()->postcode(),
            'anet_customer_profile_id' => null,
            'anet_customer_shipping_address_id' => null,
            'bonus_point' => fake()->numberBetween(0, 1000),
            'image' => null,
            'timezone' => fake()->timezone(),
            'verification_token' => null,
            'is_valid_email' => fake()->boolean(80), // 80% chance
            'refer_custom' => null,
            'pay_at' => null,
            'refer_type' => null,
            'name_as_referral' => null,
            'affiliate_token' => Str::random(16),
            'is_receive_subscribe' => fake()->boolean(70), // 70% chance
            'is_receive_list' => fake()->boolean(60), // 60% chance
            'pay_day' => null,
            'is_test' => fake()->boolean(10), // 10% chance
            'admin_token' => null,
            'is_assistant' => fake()->boolean(5), // 5% chance
            'cancel_at' => null,
            'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the user is an influencer.
     */
    public function influencer(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_influencer' => true,
            'role' => 2, // Influencer role
        ]);
    }

    /**
     * Indicate that the user is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_valid_email' => true,
            'verification_token' => null,
        ]);
    }

    /**
     * Indicate that the user is unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_valid_email' => false,
            'verification_token' => Str::random(32),
        ]);
    }

    /**
     * Indicate that the user is a test user.
     */
    public function test(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_test' => true,
            'email' => 'test+'.fake()->userName().'@example.com',
        ]);
    }
}
