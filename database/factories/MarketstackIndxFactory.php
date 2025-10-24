<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Finance\MarketstackIndx;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance\MarketstackIndx>
 */
class MarketstackIndxFactory extends Factory
{
    protected $model = MarketstackIndx::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Index',
            'symbol' => $this->faker->unique()->lexify('????'),
            'country' => $this->faker->optional()->countryCode(),
            'currency' => $this->faker->optional()->currencyCode(),
            'has_intraday' => $this->faker->boolean(60), // 60% have intraday
            'has_eod' => $this->faker->boolean(80), // 80% have end of day
            'is_active' => $this->faker->boolean(85), // 85% active
            'full' => $this->faker->optional()->randomElements(['exchange' => $this->faker->word(), 'mic' => $this->faker->word()]),
        ];
    }
}
