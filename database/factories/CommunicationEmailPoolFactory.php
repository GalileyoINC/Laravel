<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\EmailPool;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\EmailPool>
 */
class CommunicationEmailPoolFactory extends Factory
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
            'id_user' => User::factory(),
            'to_email' => $this->faker->safeEmail(),
            'subject' => $this->faker->sentence(5),
            'body' => $this->faker->paragraph(5),
            'status' => $this->faker->randomElement(['pending', 'sent', 'failed', 'bounced']),
            'sent_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 year', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create a sent email.
     */
    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sent',
            'sent_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Create a pending email.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'sent_at' => null,
        ]);
    }
}
