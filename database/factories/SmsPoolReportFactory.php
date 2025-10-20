<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\SmsPoolReport;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\SmsPoolReport>
 */
class SmsPoolReportFactory extends Factory
{
    protected $model = SmsPoolReport::class;

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
