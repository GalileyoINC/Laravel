<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Chat;

class SendMessageDTO
{
    public function __construct(
        public readonly int $conversationId,
        public readonly int $userId,
        public readonly string $message,
        public readonly ?array $files = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id_conversation' => $this->conversationId,
            'id_user' => $this->userId,
            'message' => $this->message,
        ];
    }
}
