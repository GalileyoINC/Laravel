<?php

declare(strict_types=1);

namespace App\Domain\Services\EmailTemplate;

use App\Domain\DTOs\EmailTemplate\EmailTemplateListRequestDTO;
use App\Domain\DTOs\EmailTemplate\EmailTemplateSendRequestDTO;
use App\Domain\DTOs\EmailTemplate\EmailTemplateUpdateRequestDTO;
use App\Models\Communication\EmailTemplate;
use Illuminate\Support\Facades\Mail;

class EmailTemplateService implements EmailTemplateServiceInterface
{
    public function getList(EmailTemplateListRequestDTO $dto): array
    {
        $query = EmailTemplate::query();

        if ($dto->search) {
            $query->where(function ($q) use ($dto) {
                $q->where('name', 'like', '%'.$dto->search.'%')
                    ->orWhere('subject', 'like', '%'.$dto->search.'%');
            });
        }

        if ($dto->status !== null) {
            $query->where('status', $dto->status);
        }

        $templates = $query->orderBy('created_at', 'desc')
            ->paginate($dto->limit, ['*'], 'page', $dto->page);

        return [
            'data' => $templates->items(),
            'pagination' => [
                'current_page' => $templates->currentPage(),
                'last_page' => $templates->lastPage(),
                'per_page' => $templates->perPage(),
                'total' => $templates->total(),
            ],
        ];
    }

    public function getById(int $id): EmailTemplate
    {
        return EmailTemplate::findOrFail($id);
    }

    public function update(EmailTemplateUpdateRequestDTO $dto): EmailTemplate
    {
        $template = EmailTemplate::findOrFail($dto->id);

        $updateData = [];
        if ($dto->name !== null) {
            $updateData['name'] = $dto->name;
        }
        if ($dto->subject !== null) {
            $updateData['subject'] = $dto->subject;
        }
        if ($dto->body !== null) {
            $updateData['body'] = $dto->body;
        }
        if ($dto->params !== null) {
            $updateData['params'] = json_encode($dto->params);
        }
        if ($dto->status !== null) {
            $updateData['status'] = $dto->status;
        }

        if (! empty($updateData)) {
            $template->update($updateData);
        }

        return $template;
    }

    public function getBody(int $id): array
    {
        $template = EmailTemplate::findOrFail($id);

        // Mock body rendering - replace with actual template rendering
        $params = $template->params ?? [];
        $exampleParams = array_map(fn ($param) => $param['example'] ?? 'Example Value', $params);

        return [
            'template_id' => $template->id,
            'name' => $template->name,
            'subject' => $template->subject,
            'body' => $template->body,
            'rendered_body' => $this->renderTemplate($template->body, $exampleParams),
            'params' => $params,
        ];
    }

    public function sendAdminEmail(EmailTemplateSendRequestDTO $dto): array
    {
        $template = EmailTemplate::findOrFail($dto->id);

        $subject = $dto->subject ?? $template->subject;
        $body = $dto->body ?? $template->body;

        // Render template with params
        $renderedBody = $this->renderTemplate($body, $dto->params);

        // Send email using Laravel Mail
        Mail::send([], [], function ($message) use ($dto, $subject, $renderedBody) {
            $message->to($dto->to)
                ->subject($subject)
                ->setBody($renderedBody, 'text/html');
        });

        return [
            'template_id' => $template->id,
            'to' => $dto->to,
            'subject' => $subject,
            'sent_at' => now(),
        ];
    }

    /**
     * @param  array<string, mixed>  $params
     */
    private function renderTemplate(string $body, array $params): string
    {
        foreach ($params as $key => $value) {
            $body = str_replace('{'.$key.'}', $value, $body);
        }

        return $body;
    }
}
