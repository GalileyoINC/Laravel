<?php

declare(strict_types=1);

namespace App\Modules\Content\Application\DTOs\PublicFeed;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class PublicFeedImageUploadRequestDTO
{
    public function __construct(
        public readonly string $uuid,
        public readonly UploadedFile $file
    ) {}

    public static function fromData(string $uuid, UploadedFile $file): static
    {
        return new self(
            uuid: $uuid,
            file: $file
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            uuid: $request->input('uuid'),
            file: $request->file('file')
        );
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'file' => $this->file,
        ];
    }

    public function validate(): bool
    {
        return ! empty($this->uuid) && $this->file !== null;
    }
}
