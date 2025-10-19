<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailTemplate;

use App\Models\Communication\EmailTemplate;

final class ExportEmailTemplatesToCsvAction
{
    public function execute(array $filters): array
    {
        $query = EmailTemplate::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('from', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }
        if (! empty($filters['subject'])) {
            $query->where('subject', 'like', "%{$filters['subject']}%");
        }
        if (! empty($filters['from'])) {
            $query->where('from', 'like', "%{$filters['from']}%");
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        $emailTemplates = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Name', 'Subject', 'From', 'Created At'];
        foreach ($emailTemplates as $emailTemplate) {
            $rows[] = [
                $emailTemplate->id,
                $emailTemplate->name,
                $emailTemplate->subject,
                $emailTemplate->from,
                $emailTemplate->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
