<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Content;

readonly class PageContentCreateDTO
{
    public function __construct(
        public int $pageId,
        public string $content,
        public int $status,
    ) {}
}
