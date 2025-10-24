<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\Conversation;
use App\Models\Communication\ConversationMessage;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\ConversationMessage>
 */
class ConversationMessageFactory extends Factory
{
    protected $model = ConversationMessage::class;

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
            'id_conversation' => $conversation->id,
            'id_user' => $this->faker->numberBetween(1, 100), // Use existing user IDs
            'message' => $this->faker->paragraph(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'received_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'token' => $this->faker->optional()->uuid(),
        ];
    }

    /**
     * Create a text message.
     */
    public function text(): static
    {
        return $this->state(fn (array $attributes) => [
            'message_type' => 'text',
        ]);
    }

    /**
     * Create an image message.
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'message_type' => 'image',
            'message' => 'Image: '.$this->faker->imageUrl(),
        ]);
    }

    /**
     * Create a read message.
     */
    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => true,
        ]);
    }
}
