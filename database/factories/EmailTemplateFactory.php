<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\EmailTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\EmailTemplate>
 */
class EmailTemplateFactory extends Factory
{
    protected $model = EmailTemplate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'subject' => $this->faker->sentence(5),
            'body' => $this->faker->paragraph(5),
            'template_type' => $this->faker->randomElement(['welcome', 'notification', 'promotional', 'system']),
            'is_active' => $this->faker->boolean(80),
            'variables' => json_encode([
                'user_name' => '{{user_name}}',
                'app_name' => '{{app_name}}',
                'unsubscribe_link' => '{{unsubscribe_link}}',
            ]),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create a welcome template.
     */
    public function welcome(): static
    {
        return $this->state(fn (array $attributes) => [
            'template_type' => 'welcome',
            'subject' => 'Welcome to {{app_name}}!',
        ]);
    }

    /**
     * Create a notification template.
     */
    public function notification(): static
    {
        return $this->state(fn (array $attributes) => [
            'template_type' => 'notification',
            'subject' => 'Notification: {{subject}}',
        ]);
    }

    /**
     * Create an active template.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }
}
