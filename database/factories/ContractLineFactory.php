<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\ContractLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\ContractLine>
 */
class ContractLineFactory extends Factory
{
    protected $model = ContractLine::class;

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
