<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\Conversation;
use App\Models\Communication\ConversationUnviewed;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\ConversationUnviewed>
 */
class ConversationUnviewedFactory extends Factory
{
    protected $model = ConversationUnviewed::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure Conversation exists, create if not
        $conversation = Conversation::first();
        if (!$conversation) {
            $conversation = Conversation::factory()->create();
        }

        return [
            'id_user' => $this->faker->numberBetween(1, 100), // Use existing user IDs
            'id_conversation' => $conversation->id,
            'id_conversation_message' => $this->faker->optional()->numberBetween(1, 100),
        ];
    }

    /**
     * Create with many unviewed messages.
     */
    public function manyUnviewed(): static
    {
        return $this->state(fn (array $attributes) => [
            'unviewed_count' => $this->faker->numberBetween(5, 20),
        ]);
    }

    /**
     * Create with few unviewed messages.
     */
    public function fewUnviewed(): static
    {
        return $this->state(fn (array $attributes) => [
            'unviewed_count' => $this->faker->numberBetween(1, 3),
        ]);
    }
}
