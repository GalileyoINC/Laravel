<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\EmailPoolAttachment;
use App\Models\Communication\EmailPool;
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
        // Ensure EmailPool exists, create if not
        $emailPool = EmailPool::first();
        if (!$emailPool) {
            $emailPool = EmailPool::factory()->create();
        }

        return [
            'id_email_pool' => $emailPool->id,
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
