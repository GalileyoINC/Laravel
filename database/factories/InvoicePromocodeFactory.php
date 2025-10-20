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
            //
        ];
    }
}
