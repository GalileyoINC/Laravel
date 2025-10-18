<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\Conversation;
use App\Models\Communication\ConversationFile;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\ConversationFile>
 */
class CommunicationConversationFileFactory extends Factory
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
            'id_conversation' => Conversation::factory(),
            'id_user' => User::factory(),
            'filename' => $this->faker->word().'.'.$this->faker->fileExtension(),
            'original_filename' => $this->faker->word().'.'.$this->faker->fileExtension(),
            'file_path' => 'conversations/'.$this->faker->uuid().'/'.$this->faker->word().'.'.$this->faker->fileExtension(),
            'file_size' => $this->faker->numberBetween(1024, 10485760), // 1KB to 10MB
            'mime_type' => $this->faker->mimeType(),
            'uploaded_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
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
