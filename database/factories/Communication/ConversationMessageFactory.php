<?php

declare(strict_types=1);

namespace Database\Factories\Communication;

use App\Models\Communication\Conversation;
use App\Models\Communication\ConversationMessage;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\ConversationMessage>
 */
class ConversationMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConversationMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_conversation' => Conversation::factory(),
            'id_user' => User::factory(),
            'message' => fake()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
