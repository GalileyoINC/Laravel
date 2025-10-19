<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Content;

readonly class PageContentUpdateDTO
{
    public function __construct(
        public int $id,
        public string $content,
        public int $status,
    ) {}
}
