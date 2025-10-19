<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Subscription;

readonly class SubscriptionDeactivateRequestDTO
{
    public function __construct(
        public int $id,
    ) {}
}
