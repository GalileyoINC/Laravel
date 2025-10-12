<?php

declare(strict_types=1);

namespace App\DTOs\Report;

readonly class ReportCsvRequestDTO
{
    public function __construct(
        public ?string $name,
        public bool $csv,
        public int $page,
        public int $limit
    ) {}
}
