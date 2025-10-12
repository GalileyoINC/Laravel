<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Subscription\Subscription;
use App\Models\User\User;
use App\Models\User\UserSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\UserSubscription>
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
            'id_user' => User::factory(),
            'id_subscription' => Subscription::factory(),
        ];
    }
}
