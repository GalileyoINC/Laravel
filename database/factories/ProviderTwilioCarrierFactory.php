<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\ProviderTwilioCarrier;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\ProviderTwilioCarrier>
 */
class ProviderTwilioCarrierFactory extends Factory
{
    protected $model = ProviderTwilioCarrier::class;

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
