<?php

declare(strict_types=1);

namespace Database\Factories;

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
            'id_email_pool' => $this->faker->numberBetween(1, 150), // Use existing email pool IDs from DemoDataSeeder
            'body' => $this->faker->text(1000),
            'file_name' => $this->faker->word().'.'.$this->faker->fileExtension(),
            'content_type' => $this->faker->mimeType(),
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
