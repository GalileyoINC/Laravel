<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Comment;

use Illuminate\Http\Request;

class CommentUpdateRequestDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $message
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            id: (int) $data['id'],
            message: $data['message']
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            id: $request->input('id'),
            message: $request->input('message')
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
