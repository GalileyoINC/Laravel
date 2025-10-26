<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\User;
use App\Models\Product;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSubscription>
 */
class UserSubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserSubscription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'product_id' => 1,
            'credit_card_id' => null,
            'status' => 'active',
            'price' => fake()->randomFloat(2, 9.99, 99.99),
            'start_date' => now(),
            'end_date' => null,
            'cancel_at' => null,
            'is_cancelled' => false,
            'can_reactivate' => true,
            'settings' => null,
            'payment_method' => 'credit_card',
            'external_id' => null,
        ];
    }
}
