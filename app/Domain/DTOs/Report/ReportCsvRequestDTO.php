<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Report;

readonly class ReportCsvRequestDTO
{
    public function __construct(
        public ?string $name,
        public bool $csv,
        public int $page,
        public int $limit
    ) {}
}
