<?php

declare(strict_types=1);

namespace App\Domain\DTOs\PrivateFeed;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class PrivateFeedCreateRequestDTO
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description = null,
        public readonly ?UploadedFile $imageFile = null
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            title: $data['title'],
            description: $data['description'] ?? null,
            imageFile: $data['image_file'] ?? null
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            title: $request->input('title'),
            description: $request->input('description'),
            imageFile: $request->file('image_file')
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'image_file' => $this->imageFile,
        ];
    }

    public function validate(): bool
    {
        return ! empty($this->title);
    }
}
