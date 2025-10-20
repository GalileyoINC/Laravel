<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order\AppleAppTransaction;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order\AppleAppTransaction>
 */
class AppleAppTransactionFactory extends Factory
{
    protected $model = AppleAppTransaction::class;

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
