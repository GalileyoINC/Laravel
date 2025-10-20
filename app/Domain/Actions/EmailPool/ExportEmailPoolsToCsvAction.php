<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailPool;

use App\Models\Communication\EmailPool;

final class ExportEmailPoolsToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<list<mixed>>
     */
    public function execute(array $filters): array
    {
        $query = EmailPool::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('from', 'like', "%{$search}%")
                    ->orWhere('to', 'like', "%{$search}%")
                    ->orWhere('reply', 'like', "%{$search}%")
                    ->orWhere('bcc', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        $emailPools = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Type', 'Status', 'From', 'To', 'Reply', 'BCC', 'Subject', 'Created At'];
        foreach ($emailPools as $emailPool) {
            $rows[] = [
                $emailPool->id,
                $emailPool->type,
                $emailPool->status,
                $emailPool->from,
                $emailPool->to,
                $emailPool->reply,
                $emailPool->bcc,
                $emailPool->subject,
                $emailPool->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
