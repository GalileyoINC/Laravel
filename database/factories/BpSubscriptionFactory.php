<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Subscription\BpSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription\BpSubscription>
 */
class BpSubscriptionFactory extends Factory
{
    protected $model = BpSubscription::class;

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
