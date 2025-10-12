<?php

declare(strict_types=1);

namespace App\DTOs\Report;

readonly class ReportStatisticRequestDTO
{
    public function __construct(
        public ?string $date,
        public int $page,
        public int $limit
    ) {}
}
