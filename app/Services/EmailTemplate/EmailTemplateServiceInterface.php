<?php

declare(strict_types=1);

namespace App\Services\EmailTemplate;

use App\DTOs\EmailTemplate\EmailTemplateListRequestDTO;
use App\DTOs\EmailTemplate\EmailTemplateSendRequestDTO;
use App\DTOs\EmailTemplate\EmailTemplateUpdateRequestDTO;
use App\Models\Communication\EmailTemplate;

interface EmailTemplateServiceInterface
{
    public function getList(EmailTemplateListRequestDTO $dto): array;

    public function getById(int $id): EmailTemplate;

    public function update(EmailTemplateUpdateRequestDTO $dto): EmailTemplate;

    public function getBody(int $id): array;

    public function sendAdminEmail(EmailTemplateSendRequestDTO $dto): array;
}
