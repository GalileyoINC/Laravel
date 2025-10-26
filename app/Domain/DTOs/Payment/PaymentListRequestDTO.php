<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Payment;

/**
 * PaymentListRequestDTO
 * DTO for payment list requests with pagination
 */
class PaymentListRequestDTO
{
    public function __construct(
        public readonly int $limit = 100,
        public readonly int $page = 1,
        public readonly ?string $type = null,
        public readonly ?bool $is_success = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            limit: isset($data['limit']) ? (int) $data['limit'] : 100,
            page: isset($data['page']) ? (int) $data['page'] : 1,
            type: $data['type'] ?? null,
            is_success: $data['is_success'] ?? null,
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
            'type' => $this->type,
            'is_success' => $this->is_success,
        ];
    }
}
