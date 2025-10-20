<?php

declare(strict_types=1);

namespace App\Domain\Actions\Twilio;

use App\Models\System\TwilioIncoming;

final class ExportTwilioIncomingToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<list<mixed>>
     */
    public function execute(array $filters): array
    {
        $query = TwilioIncoming::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['number'])) {
            $query->where('number', 'like', "%{$filters['number']}%");
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Number', 'Body', 'Message', 'Created At'];
        foreach ($items as $message) {
            $rows[] = [
                $message->id,
                $message->number,
                $message->body,
                $message->message,
                $message->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
