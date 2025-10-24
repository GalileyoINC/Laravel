<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\MoneyTransaction;
use App\Models\Finance\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\MoneyTransaction>
 */
class MoneyTransactionFactory extends Factory
{
    protected $model = MoneyTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure Invoice exists, create if not
        $invoice = Invoice::first();
        if (!$invoice) {
            $invoice = Invoice::factory()->create();
        }

        return [
            'id_user' => $this->faker->numberBetween(1, 100), // Use existing user IDs
            'id_invoice' => $invoice->id,
            'id_credit_card' => $this->faker->optional()->numberBetween(1, 75),
            'transaction_type' => $this->faker->numberBetween(1, 5),
            'transaction_id' => $this->faker->optional()->uuid(),
            'is_success' => $this->faker->boolean(80), // 80% success rate
            'total' => $this->faker->randomFloat(2, 0, 1000),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'is_void' => $this->faker->boolean(5), // 5% void rate
            'id_refund' => $this->faker->optional()->numberBetween(1, 50),
            'is_test' => $this->faker->boolean(10), // 10% test transactions
            'error' => $this->faker->optional()->sentence(),
            'note' => $this->faker->optional()->paragraph(),
        ];
    }
}
