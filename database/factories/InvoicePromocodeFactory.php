<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\InvoicePromocode;
use App\Models\Finance\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\InvoicePromocode>
 */
class InvoicePromocodeFactory extends Factory
{
    protected $model = InvoicePromocode::class;

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
            'id_promo' => $this->faker->numberBetween(1, 20), // Use existing promocode IDs
            'id_invoice' => $invoice->id,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
