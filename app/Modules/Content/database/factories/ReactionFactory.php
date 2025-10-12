<?php

declare(strict_types=1);

namespace App\Modules\Content\database\factories;

use App\Models\Content\Reaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content\Reaction>
 */
class ReactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $emojis = ['👍', '👎', '❤️', '😀', '😢', '😮', '😡', '🔥', '💯', '🎉', '👏', '🤔', '😍', '😂', '😭'];

        return [
            'emoji' => fake()->randomElement($emojis),
        ];
    }

    /**
     * Indicate that the reaction is a like.
     */
    public function like(): static
    {
        return $this->state(fn (array $attributes) => [
            'emoji' => '👍',
        ]);
    }

    /**
     * Indicate that the reaction is a dislike.
     */
    public function dislike(): static
    {
        return $this->state(fn (array $attributes) => [
            'emoji' => '👎',
        ]);
    }

    /**
     * Indicate that the reaction is a heart.
     */
    public function heart(): static
    {
        return $this->state(fn (array $attributes) => [
            'emoji' => '❤️',
        ]);
    }

    /**
     * Indicate that the reaction is a fire.
     */
    public function fire(): static
    {
        return $this->state(fn (array $attributes) => [
            'emoji' => '🔥',
        ]);
    }
}
