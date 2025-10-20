<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $first = fake()->firstName();
        $last = fake()->boolean(80) ? fake()->lastName() : null;
        // Generate unique email using timestamp and random string to avoid duplicates
        $email = fake()->unique()->userName() . '_' . time() . '_' . bin2hex(random_bytes(4)) . '@example.com';

        return [
            'email' => $email,
            'auth_key' => bin2hex(random_bytes(16)),
            'password_hash' => null,
            'password_reset_token' => null,
            'role' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'first_name' => $first,
            'last_name' => $last,
            'phone_profile' => fake()->optional()->e164PhoneNumber(),
            'country' => fake()->optional()->countryISOAlpha3(),
            'state' => fake()->optional()->stateAbbr(),
            'is_influencer' => false,
            'zip' => fake()->optional()->postcode(),
            'anet_customer_profile_id' => null,
            'anet_customer_shipping_address_id' => null,
            'bonus_point' => 0,
            'image' => null,
            'timezone' => fake()->optional()->timezone(),
            'verification_token' => null,
            'is_valid_email' => true,
            'refer_custom' => null,
            'pay_at' => null,
            'refer_type' => null,
            'name_as_referral' => null,
            'affiliate_token' => null,
            'is_receive_subscribe' => true,
            'is_receive_list' => true,
            'pay_day' => null,
            'is_test' => false,
            'admin_token' => null,
            'is_assistant' => false,
            'cancel_at' => null,
            'tour' => null,
            'id_sps' => null,
            'sps_data' => null,
            'is_sps_active' => false,
            'sps_terminated_at' => null,
            'general_visibility' => 0,
            'phone_visibility' => 1,
            'address_visibility' => 0,
            'last_price' => null,
            'credit' => 0,
            'is_bad_email' => false,
            'promocode' => null,
            'test_message_qnt' => 0,
            'test_message_at' => null,
            'city' => fake()->optional()->city(),
            'id_inviter' => null,
            'source' => null,
            'about' => fake()->optional()->paragraph(),
            'header_image' => null,
            'name_search' => $first.' '.($last ?? ''),
        ];
    }

    public function influencer(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_influencer' => true,
        ]);
    }
}
