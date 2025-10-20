<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\SmsPoolArchive;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\SmsPoolArchive>
 */
class SmsPoolArchiveFactory extends Factory
{
    protected $model = SmsPoolArchive::class;

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
