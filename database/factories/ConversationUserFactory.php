<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\ConversationUser;
use App\Models\Communication\Conversation;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\ConversationUser>
 */
class ConversationUserFactory extends Factory
{
    protected $model = ConversationUser::class;

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
            'id_user' => $this->faker->numberBetween(1, 100),
        ];
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Create a moderator user.
     */
    public function moderator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'moderator',
        ]);
    }

    /**
     * Create a regular member.
     */
    public function member(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'member',
        ]);
    }
}
