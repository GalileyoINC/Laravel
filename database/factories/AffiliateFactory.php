<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\Affiliate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\Affiliate>
 */
class AffiliateFactory extends Factory
{
    protected $model = Affiliate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user_parent' => $this->faker->numberBetween(1, 100),
            'id_user_child' => $this->faker->numberBetween(1, 100),
            'is_active' => $this->faker->boolean(),
            'params' => $this->faker->optional()->randomElement(['key1' => 'value1', 'key2' => 'value2']),
        ];
    }
}
