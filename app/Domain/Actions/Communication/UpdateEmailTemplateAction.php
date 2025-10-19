<?php

declare(strict_types=1);

namespace App\Domain\Actions\Communication;

use App\Domain\DTOs\Communication\EmailTemplateUpdateDTO;
use App\Models\Communication\EmailTemplate;

final class UpdateEmailTemplateAction
{
    public function execute(EmailTemplateUpdateDTO $dto): EmailTemplate
    {
        $template = EmailTemplate::findOrFail($dto->id);
        $template->fromEmail = $dto->fromEmail;
        $template->fromName = $dto->fromName;
        $template->subject = $dto->subject;
        $template->body = $dto->body;
        $template->bodyPlain = $dto->bodyPlain;
        $template->save();

        return $template;
    }
}
