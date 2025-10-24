<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\System\ZipU;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System\ZipU>
 */
class ZipUFactory extends Factory
{
    protected $model = ZipU::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'zip' => $this->faker->postcode(),
            'geopoint' => $this->faker->optional()->latitude() . ',' . $this->faker->optional()->longitude(),
            'city' => $this->faker->optional()->city(),
            'state' => $this->faker->optional()->stateAbbr(),
            'timezone' => $this->faker->optional()->timezone(),
            'daylight_savings_time_flag' => $this->faker->optional()->randomElement(['Y', 'N']),
        ];
    }
}
