<?php

declare(strict_types=1);

namespace App\Domain\DTOs\AllSendForm;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class AllSendImageUploadRequestDTO
{
    public function __construct(
        public readonly string $uuid,
        public readonly UploadedFile $file
    ) {}

    public static function fromData(string $uuid, UploadedFile $file): self
    {
        return new self(
            uuid: $uuid,
            file: $file
        );
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            uuid: $request->input('uuid'),
            file: $request->file('file')
        );
    }

    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'file' => $this->file,
        ];
    }

    public function validate(): bool
    {
        return ! empty($this->uuid);
    }
}
