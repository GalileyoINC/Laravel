<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\UserSatelliteSubscription;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\UserSatelliteSubscription>
 */
class UserSatelliteSubscriptionFactory extends Factory
{
    protected $model = UserSatelliteSubscription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
