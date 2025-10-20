<?php

declare(strict_types=1);

namespace App\Domain\Services\EmailPool;

use App\Domain\DTOs\EmailPool\EmailPoolListRequestDTO;
use App\Models\Communication\EmailPool;
use App\Models\Communication\EmailPoolAttachment;
use Illuminate\Support\Facades\Mail;

class EmailPoolService implements EmailPoolServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getList(EmailPoolListRequestDTO $dto): array
    {
        $query = EmailPool::query();

        if ($dto->search) {
            $query->where(function ($q) use ($dto) {
                $q->where('subject', 'like', '%'.$dto->search.'%')
                    ->orWhere('to', 'like', '%'.$dto->search.'%')
                    ->orWhere('from', 'like', '%'.$dto->search.'%');
            });
        }

        if ($dto->status) {
            $query->where('status', $dto->status);
        }

        if ($dto->to) {
            $query->where('to', 'like', '%'.$dto->to.'%');
        }

        $emails = $query->orderBy('created_at', 'desc')
            ->paginate($dto->limit, ['*'], 'page', $dto->page);

        return [
            'data' => $emails->items(),
            'pagination' => [
                'current_page' => $emails->currentPage(),
                'last_page' => $emails->lastPage(),
                'per_page' => $emails->perPage(),
                'total' => $emails->total(),
            ],
        ];
    }

    public function getById(int $id): EmailPool
    {
        return EmailPool::with(['attachments'])->findOrFail($id);
    }

    public function delete(int $id): void
    {
        $email = EmailPool::findOrFail($id);
        $email->delete();
    }

    /**
     * @return array<string, mixed>
     */
    public function resend(int $id): array
    {
        $emailPool = EmailPool::findOrFail($id);

        // Parse JSON fields
        $to = json_decode((string) $emailPool->to, true);
        $from = json_decode((string) $emailPool->from, true);

        // Send email using Laravel Mail
        Mail::send([], [], function ($message) use ($emailPool, $to, $from) {
            $message->to($to)
                ->from($from)
                ->subject($emailPool->subject)
                ->setBody($emailPool->body, 'text/html');
        });

        return [
            'id' => $emailPool->id,
            'subject' => $emailPool->subject,
            'to' => $to,
            'from' => $from,
            'resent_at' => now(),
        ];
    }

    public function getAttachment(int $id): EmailPoolAttachment
    {
        return EmailPoolAttachment::findOrFail($id);
    }
}
