<?php

declare(strict_types=1);

namespace App\DTOs\Comment;

use Illuminate\Http\Request;

class CommentUpdateRequestDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $message
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            id: (int) $data['id'],
            message: $data['message']
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            id: $request->input('id'),
            message: $request->input('message')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
        ];
    }

    public function validate(): bool
    {
        if ($this->id <= 0) {
            return false;
        }

        if (empty($this->message)) {
            return false;
        }

        return true;
    }
}
