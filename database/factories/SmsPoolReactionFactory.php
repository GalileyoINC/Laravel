<?php

namespace Database\Factories;

use App\Models\SmsPoolReaction;
use App\Models\SmsPool;
use App\Models\User;
use App\Models\Reaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SmsPoolReaction>
 */
class SmsPoolReactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmsPoolReaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_sms_pool' => SmsPool::factory(),
            'id_user' => User::factory(),
            'id_reaction' => Reaction::factory(),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}