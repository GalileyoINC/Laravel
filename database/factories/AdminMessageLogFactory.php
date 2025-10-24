<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\System\AdminMessageLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System\AdminMessageLog>
 */
class AdminMessageLogFactory extends Factory
{
    protected $model = AdminMessageLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_staff' => $this->faker->numberBetween(1, 15), // Use existing staff IDs
            'obj_type' => $this->faker->optional()->numberBetween(1, 5),
            'obj_id' => $this->faker->optional()->randomNumber(5),
            'body' => $this->faker->optional()->paragraph(),
            'created_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
