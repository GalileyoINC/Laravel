<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailPoolArchive;

use App\Models\Communication\EmailPoolArchive;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetEmailPoolArchiveListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = EmailPoolArchive::with(['attachments']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('to', 'like', "%{$search}%")
                    ->orWhere('from', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['from'])) {
            $query->where('from', 'like', "%{$filters['from']}%");
        }
        if (! empty($filters['to'])) {
            $query->where('to', 'like', "%{$filters['to']}%");
        }
        if (! empty($filters['subject'])) {
            $query->where('subject', 'like', "%{$filters['subject']}%");
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }
        if (! empty($filters['updated_at_from'])) {
            $query->whereDate('updated_at', '>=', $filters['updated_at_from']);
        }
        if (! empty($filters['updated_at_to'])) {
            $query->whereDate('updated_at', '<=', $filters['updated_at_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
