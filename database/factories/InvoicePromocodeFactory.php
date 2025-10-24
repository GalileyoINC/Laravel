<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\InvoicePromocode;
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
        return [
            'id_promo' => $this->faker->numberBetween(1, 20), // Use existing promocode IDs
            'id_invoice' => $this->faker->numberBetween(1, 50), // Use existing invoice IDs from DemoDataSeeder
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
