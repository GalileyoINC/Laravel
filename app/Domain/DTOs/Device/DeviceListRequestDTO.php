<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Device;

readonly class DeviceListRequestDTO
{
    public function __construct(
        public int $page,
        public int $limit,
        public ?string $search,
        public ?int $user_id,
        public ?string $os,
        public ?int $pushTokenFill = null,
        public ?string $pushToken = null,
        public ?int $pushTurnOn = null,
        public ?string $updatedAtFrom = null,
        public ?string $updatedAtTo = null
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'limit' => $this->limit,
            'search' => $this->search,
            'user_id' => $this->user_id,
            'os' => $this->os,
            'push_token_fill' => $this->pushTokenFill,
            'push_token' => $this->pushToken,
            'push_turn_on' => $this->pushTurnOn,
            'updated_at_from' => $this->updatedAtFrom,
            'updated_at_to' => $this->updatedAtTo,
        ];
    }
}
