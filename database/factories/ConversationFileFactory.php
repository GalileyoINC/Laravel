<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\ConversationFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\ConversationFile>
 */
class ConversationFileFactory extends Factory
{
    protected $model = ConversationFile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_conversation' => $this->faker->numberBetween(1, 50),
            'id_conversation_message' => null, // Set to null to avoid foreign key constraint
            'folder_name' => $this->faker->optional()->word(),
            'web_name' => $this->faker->optional()->word().'.'.$this->faker->fileExtension(),
            'file_name' => $this->faker->word().'.'.$this->faker->fileExtension(),
            'sizes' => $this->faker->optional()->randomElement(['width' => 800, 'height' => 600]),
        ];
    }

    /**
     * Create an image file.
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
     * Create a document file.
     */
    public function document(): static
    {
        return $this->state(fn (array $attributes) => [
            'filename' => $this->faker->word().'.pdf',
            'original_filename' => $this->faker->word().'.pdf',
            'mime_type' => 'application/pdf',
        ]);
    }
}
