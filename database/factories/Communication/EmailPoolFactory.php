<?php

declare(strict_types=1);

namespace Database\Factories\Communication;

use App\Models\Communication\EmailPool;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\EmailPool>
 */
class EmailPoolFactory extends Factory
{
    protected $model = EmailPool::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement([0, 1, 2, 3]), // STATUS_PENDING, STATUS_SENT, STATUS_FAILED, STATUS_CANCELLED
            'type' => $this->faker->randomElement([0, 1, 2, 3]), // General, Marketing, Notification, System
            'to' => $this->faker->safeEmail(),
            'from' => $this->faker->safeEmail(),
            'reply' => $this->faker->optional()->safeEmail(),
            'bcc' => $this->faker->optional()->safeEmail(),
            'subject' => $this->faker->sentence(5),
            'body' => $this->faker->paragraph(5),
            'bodyPlain' => $this->faker->optional()->paragraph(5),
        ];
    }

    /**
     * Create a sent email.
     */
    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EmailPool::STATUS_SENT,
        ]);
    }

    /**
     * Create a pending email.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EmailPool::STATUS_PENDING,
        ]);
    }
}
