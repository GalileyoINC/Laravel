<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\CreditCard;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreditCard>
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
            'user_id' => User::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'num' => '****'.fake()->numerify('####'),
            'cvv' => encrypt(fake()->numerify('###')),
            'type' => fake()->randomElement(['Visa', 'MasterCard', 'American Express']),
            'expiration_year' => (int) now()->addYears(rand(1, 5))->format('Y'),
            'expiration_month' => rand(1, 12),
            'zip' => fake()->postcode(),
            'phone' => fake()->e164PhoneNumber(),
            'is_active' => true,
            'is_preferred' => false,
            'is_agree_to_receive' => fake()->boolean(),
            'anet_customer_payment_profile_id' => null,
            'anet_profile_deleted' => false,
        ];
    }
}
