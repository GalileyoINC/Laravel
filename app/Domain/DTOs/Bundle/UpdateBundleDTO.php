<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Bundle;

class UpdateBundleDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ?int $type = null,
        public readonly ?int $payInterval = null,
        public readonly ?bool $isActive = null,
        public readonly ?float $total = null
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'pay_interval' => $this->payInterval,
            'is_active' => $this->isActive,
            'total' => $this->total,
        ];
    }
}
