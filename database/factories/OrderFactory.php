<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\CreditCard;
use App\Models\Finance\Service;
use App\Models\Order;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'id_product' => Service::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'total_amount' => $this->faker->randomFloat(2, 10, 1000),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'apple_pay']),
            'status' => $this->faker->randomElement(['pending', 'paid', 'cancelled', 'refunded']),
            'is_paid' => $this->faker->boolean(),
            'notes' => $this->faker->optional()->sentence(),
            'product_details' => [
                'name' => $this->faker->word(),
                'description' => $this->faker->sentence(),
            ],
            'id_credit_card' => CreditCard::factory(),
            'payment_reference' => $this->faker->optional()->uuid(),
            'payment_details' => [
                'transaction_id' => $this->faker->uuid(),
                'gateway' => 'test_gateway',
            ],
        ];
    }

    /**
     * Indicate that the order is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'is_paid' => true,
        ]);
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'is_paid' => false,
        ]);
    }
}
