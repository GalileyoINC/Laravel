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
class CommunicationConversationUnviewedFactory extends Factory
{
    protected $model = ConversationUnviewed::class;

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
            'unviewed_count' => $this->faker->numberBetween(1, 10),
            'last_message_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
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
