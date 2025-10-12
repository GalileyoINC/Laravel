<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CreditCard;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreditCard>
 */
class CreditCardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CreditCard::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cardTypes = ['Visa', 'MasterCard', 'American Express', 'Discover'];
        $cardType = fake()->randomElement($cardTypes);

        return [
            'id_user' => User::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'num' => $this->generateCardNumber($cardType),
            'cvv' => fake()->numberBetween(100, 999),
            'type' => $cardType,
            'expiration_year' => fake()->numberBetween(2025, 2030),
            'expiration_month' => fake()->numberBetween(1, 12),
            'is_active' => fake()->boolean(90), // 90% chance
            'is_preferred' => fake()->boolean(20), // 20% chance
            'anet_customer_payment_profile_id' => fake()->boolean(60) ? fake()->uuid() : null,
            'anet_profile_deleted' => fake()->boolean(5), // 5% chance
            'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the credit card is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the credit card is preferred.
     */
    public function preferred(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_preferred' => true,
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the credit card is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiration_year' => fake()->numberBetween(2020, 2023),
            'expiration_month' => fake()->numberBetween(1, 12),
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the credit card is a Visa.
     */
    public function visa(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Visa',
            'num' => '4'.fake()->numerify('###############'),
        ]);
    }

    /**
     * Indicate that the credit card is a MasterCard.
     */
    public function mastercard(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'MasterCard',
            'num' => fake()->randomElement(['5', '2']).fake()->numerify('###############'),
        ]);
    }

    /**
     * Generate a realistic card number based on type.
     */
    private function generateCardNumber(string $type): string
    {
        return match ($type) {
            'Visa' => '4'.fake()->numerify('###############'),
            'MasterCard' => fake()->randomElement(['5', '2']).fake()->numerify('###############'),
            'American Express' => fake()->randomElement(['34', '37']).fake()->numerify('#############'),
            'Discover' => '6'.fake()->numerify('###############'),
            default => fake()->numerify('################'),
        };
    }
}
