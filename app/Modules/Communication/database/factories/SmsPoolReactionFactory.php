<?php

declare(strict_types=1);

namespace App\Modules\Communication\database\factories;

use App\Models\Communication\SmsPool;
use App\Models\Communication\SmsPoolReaction;
use App\Models\Content\Reaction;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\SmsPoolReaction>
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
