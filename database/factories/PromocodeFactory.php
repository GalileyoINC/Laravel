<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\Promocode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\Promocode>
 */
class PromocodeFactory extends Factory
{
    protected $model = Promocode::class;

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
