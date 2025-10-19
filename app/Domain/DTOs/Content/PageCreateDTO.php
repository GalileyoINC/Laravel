<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Content;

readonly class PageCreateDTO
{
    public function __construct(
        public string $name,
        public ?string $title,
        public string $slug,
        public ?string $metaKeywords,
        public ?string $metaDescription,
        public int $status,
    ) {}
}
