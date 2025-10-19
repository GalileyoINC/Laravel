<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Bundle;

class CreateBundleDTO
{
    public function __construct(
        public readonly string $title,
        public readonly ?int $type = 1,
        public readonly ?int $payInterval = 1,
        public readonly ?bool $isActive = true,
        public readonly ?float $total = 0.0
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
            'pay_interval' => $this->payInterval,
            'is_active' => $this->isActive,
            'total' => $this->total,
        ];
    }
}
