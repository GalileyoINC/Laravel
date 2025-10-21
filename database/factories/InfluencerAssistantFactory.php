<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Subscription\InfluencerAssistant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription\InfluencerAssistant>
 */
class InfluencerAssistantFactory extends Factory
{
    protected $model = InfluencerAssistant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_influencer' => $this->faker->numberBetween(1, 100),
            'id_assistant' => $this->faker->numberBetween(1, 100),
        ];
    }
}
