<?php

declare(strict_types=1);

namespace App\Domain\Actions\Communication;

use App\Domain\DTOs\Communication\AdminSendEmailDTO;
use App\Models\Communication\EmailTemplate;
use Illuminate\Support\Facades\Mail;

final class AdminSendEmailAction
{
    public function execute(AdminSendEmailDTO $dto): bool
    {
        $template = EmailTemplate::findOrFail($dto->emailTemplateId);

        // Here you would render the template with params and send
        // For now, we simulate with a basic raw mail send
        Mail::raw($template->body, function ($message) use ($dto, $template) {
            $message->to($dto->toEmail)
                ->subject($template->subject)
                ->from($template->fromEmail, $template->fromName);
        });

        return true;
    }
}
