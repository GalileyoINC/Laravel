<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Subscription\SubscriptionWizard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription\SubscriptionWizard>
 */
class SubscriptionWizardFactory extends Factory
{
    protected $model = SubscriptionWizard::class;

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
