<?php

declare(strict_types=1);

namespace App\Domain\Actions\Logs;

use App\Models\System\ApiLog;

final class ExportApiLogsToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, array<int, mixed>>
     */
    /**
     * @return array<string, mixed>
     */
    public function execute(array $filters): array
    {
        $query = ApiLog::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['key'])) {
            $query->where('key', 'like', "%{$filters['key']}%");
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Key', 'Value', 'Created At'];
        /** @var ApiLog $log */
        foreach ($logs as $log) {
            $rows[] = [
                $log->id,
                $log->key,
                $log->value,
                $log->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
