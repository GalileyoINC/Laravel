<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Device\DevicePlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device\DevicePlan>
 */
class DevicePlanFactory extends Factory
{
    protected $model = DevicePlan::class;

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
