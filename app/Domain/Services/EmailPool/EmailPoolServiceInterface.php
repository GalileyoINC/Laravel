<?php

declare(strict_types=1);

namespace App\Domain\Services\EmailPool;

use App\Models\Communication\EmailPool;
use App\Models\Communication\EmailPoolAttachment;

interface EmailPoolServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getList(int $page, int $limit, ?string $search, ?int $status, ?string $to): array;

    public function getById(int $id): EmailPool;

    public function delete(int $id): void;

    /**
     * @return array<string, mixed>
     */
    public function resend(int $id): array;

    public function getAttachment(int $id): EmailPoolAttachment;
}
