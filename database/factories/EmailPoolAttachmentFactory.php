<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\EmailPool;
use App\Models\Communication\EmailPoolAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\EmailPoolAttachment>
 */
class EmailPoolAttachmentFactory extends Factory
{
    protected $model = EmailPoolAttachment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_email_pool' => EmailPool::factory(),
            'filename' => $this->faker->word().'.'.$this->faker->fileExtension(),
            'original_filename' => $this->faker->word().'.'.$this->faker->fileExtension(),
            'file_path' => 'email-attachments/'.$this->faker->uuid().'/'.$this->faker->word().'.'.$this->faker->fileExtension(),
            'file_size' => $this->faker->numberBetween(1024, 5242880), // 1KB to 5MB
            'mime_type' => $this->faker->mimeType(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create an image attachment.
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'filename' => $this->faker->word().'.jpg',
            'original_filename' => $this->faker->word().'.jpg',
            'mime_type' => 'image/jpeg',
        ]);
    }

    /**
     * Create a PDF attachment.
     */
    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'filename' => $this->faker->word().'.pdf',
            'original_filename' => $this->faker->word().'.pdf',
            'mime_type' => 'application/pdf',
        ]);
    }
}
