<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Payment;

/**
 * ProductListRequestDTO
 * DTO for product/plan list requests
 */
class ProductListRequestDTO
{
    public function __construct(
        public readonly int $limit = 10,
        public readonly int $page = 1,
        public readonly bool $full_info = false,
        public readonly ?string $type = null,
        public readonly ?bool $is_active = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            limit: $data['limit'] ?? 10,
            page: $data['page'] ?? 1,
            full_info: $data['full_info'] ?? false,
            type: $data['type'] ?? null,
            is_active: $data['is_active'] ?? null,
        );
    }

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public function getOffset(): int
    {
        return ($this->page - 1) * $this->limit;
    }

    public function toArray(): array
    {
        return [
            'limit' => $this->limit,
            'page' => $this->page,
            'full_info' => $this->full_info,
            'type' => $this->type,
            'is_active' => $this->is_active,
        ];
    }
}
