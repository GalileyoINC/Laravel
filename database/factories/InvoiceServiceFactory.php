<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\InvoiceService;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\InvoiceService>
 */
class InvoiceServiceFactory extends Factory
{
    protected $model = InvoiceService::class;

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
