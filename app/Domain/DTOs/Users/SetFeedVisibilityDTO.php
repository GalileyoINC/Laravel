<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Users;

class SetFeedVisibilityDTO
{
    public function __construct(
        public int $subscriptionId,
        public bool $isHidden,
    ) {}
}
