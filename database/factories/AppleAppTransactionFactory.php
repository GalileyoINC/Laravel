<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order\AppleAppTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order\AppleAppTransaction>
 */
class AppleAppTransactionFactory extends Factory
{
    protected $model = AppleAppTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' => $this->faker->uuid(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'error' => $this->faker->optional()->sentence(),
            'id_user' => $this->faker->numberBetween(1, 100),
            'data' => $this->faker->optional()->randomElements(['key1' => 'value1', 'key2' => 'value2']),
            'apple_created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'is_process' => $this->faker->boolean(),
        ];
    }
}
