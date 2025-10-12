<?php

declare(strict_types=1);

namespace App\DTOs\News;

class MuteSubscriptionRequestDTO
{
    public function __construct(
        public string $id_subscription
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id_subscription: $data['id_subscription'] ?? ''
        );
    }

    public function validate(): bool
    {
        return ! empty($this->id_subscription);
    }
}
