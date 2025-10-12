<?php

declare(strict_types=1);

namespace App\DTOs\CreditCard;

readonly class CreditCardListRequestDTO
{
    public function __construct(
        public int $page,
        public int $limit,
        public ?string $search,
        public ?int $user_id,
        public ?bool $is_active
    ) {}
}
