<?php

declare(strict_types=1);

namespace App\Modules\Content\Application\DTOs\Bundle;

readonly class BundleCreateRequestDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public float $price,
        public array $services,
        public bool $is_active
    ) {}
}
