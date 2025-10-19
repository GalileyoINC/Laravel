<?php

declare(strict_types=1);

namespace App\Domain\Actions\Communication;

use App\Domain\DTOs\Communication\AdminSendEmailDTO;
use App\Models\Communication\EmailTemplate;
use Illuminate\Support\Facades\Mail;

final class SendTestEmailAction
{
    public function execute(AdminSendEmailDTO $dto): bool
    {
        $template = EmailTemplate::findOrFail($dto->emailTemplateId);

        // Render with params if needed; simplified raw send for now
        Mail::raw($template->body, function ($message) use ($dto, $template) {
            $message->to($dto->toEmail)
                ->subject('[TEST] '.$template->subject)
                ->from($template->fromEmail, $template->fromName);
        });

        return true;
    }
}
