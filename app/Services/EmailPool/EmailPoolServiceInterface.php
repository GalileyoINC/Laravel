<?php

declare(strict_types=1);

namespace App\Services\EmailPool;

use App\DTOs\EmailPool\EmailPoolListRequestDTO;
use App\Models\Communication\EmailPool;
use App\Models\Communication\EmailPoolAttachment;

interface EmailPoolServiceInterface
{
    public function getList(EmailPoolListRequestDTO $dto): array;

    public function getById(int $id): EmailPool;

    public function delete(int $id): void;

    public function resend(int $id): array;

    public function getAttachment(int $id): EmailPoolAttachment;
}
