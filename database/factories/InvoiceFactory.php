<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\Invoice;
use App\Models\Subscription\BpSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure BpSubscription exists, create if not
        $bpSubscription = BpSubscription::first();
        if (!$bpSubscription) {
            $bpSubscription = BpSubscription::factory()->create();
        }

        return [
            'id_user' => $this->faker->numberBetween(1, 100), // Use existing user IDs
            'id_bp_subscribe' => $bpSubscription->id,
            'paid_status' => $this->faker->boolean(70), // 70% paid
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->optional()->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
