<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\SmsPoolPhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\SmsPoolPhoneNumber>
 */
class SmsPoolPhoneNumberFactory extends Factory
{
    protected $model = SmsPoolPhoneNumber::class;

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
