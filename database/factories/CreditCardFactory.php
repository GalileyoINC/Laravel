<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\CreditCard;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\CreditCard>
 */
class CreditCardFactory extends Factory
{
    protected $model = CreditCard::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'num' => (string) fake()->creditCardNumber(),
            'cvv' => (string) fake()->numberBetween(100, 999),
            'type' => fake()->randomElement(['visa', 'mastercard', 'amex']),
            'expiration_year' => (int) now()->addYears(rand(1, 5))->format('Y'),
            'expiration_month' => (int) rand(1, 12),
            'is_active' => true,
            'is_preferred' => fake()->boolean(20) ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
            'anet_customer_payment_profile_id' => null,
            'anet_profile_deleted' => 0,
            'phone' => fake()->e164PhoneNumber(),
            'zip' => fake()->postcode(),
            'is_agree_to_receive' => fake()->boolean(),
            'billing_address' => [
                'line1' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->stateAbbr(),
                'country' => fake()->countryISOAlpha3(),
            ],
        ];
    }
}
