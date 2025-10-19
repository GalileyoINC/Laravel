<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Content;

readonly class PageUpdateDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $title,
        public string $slug,
        public ?string $metaKeywords,
        public ?string $metaDescription,
        public int $status,
    ) {}
}
