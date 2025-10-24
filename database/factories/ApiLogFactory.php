<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\System\ApiLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System\ApiLog>
 */
class ApiLogFactory extends Factory
{
    protected $model = ApiLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->word() . '_' . $this->faker->randomNumber(3),
            'value' => $this->faker->optional()->paragraph(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
