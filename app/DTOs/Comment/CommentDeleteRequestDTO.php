<?php

namespace App\DTOs\Comment;

use Illuminate\Http\Request;

class CommentDeleteRequestDTO
{
    public function __construct(
        public readonly int $id
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            id: (int)$data['id']
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            id: $request->input('id')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id
        ];
    }

    public function validate(): bool
    {
        return $this->id > 0;
    }
}
