<?php

declare(strict_types=1);

namespace App\Domain\Services\EmailTemplate;

use App\Domain\DTOs\EmailTemplate\EmailTemplateSendRequestDTO;
use App\Domain\DTOs\EmailTemplate\EmailTemplateUpdateRequestDTO;
use App\Models\Communication\EmailTemplate;

interface EmailTemplateServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getList(int $page, int $limit, ?string $search, ?int $status): array;

    public function getById(int $id): EmailTemplate;

    public function update(EmailTemplateUpdateRequestDTO $dto): EmailTemplate;

    /**
     * @return array<string, mixed>
     */
    public function getBody(int $id): array;

    /**
     * @return array<string, mixed>
     */
    public function sendAdminEmail(EmailTemplateSendRequestDTO $dto): array;
}
