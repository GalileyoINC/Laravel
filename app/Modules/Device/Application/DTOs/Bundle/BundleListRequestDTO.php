<?php

declare(strict_types=1);

namespace App\Modules\Content\Application\DTOs\Bundle;

readonly class BundleListRequestDTO
{
    public function __construct(
        public int $page,
        public int $limit,
        public ?string $search,
        public ?int $status
    ) {}
}
