<?php

declare(strict_types=1);

namespace App\Domain\Actions\AdminMessageLog;

use App\Models\System\AdminMessageLog;

final class ExportAdminMessageLogsToCsvAction
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
        $query = AdminMessageLog::query();

        if (! empty($filters['objType'])) {
            $query->where('obj_type', $filters['objType']);
        }
        if (! empty($filters['objId'])) {
            $query->where('obj_id', $filters['objId']);
        }
        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where('body', 'like', "%{$search}%");
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Object Type', 'Object ID', 'Message', 'Created At'];
        /** @var AdminMessageLog $log */
        foreach ($logs as $log) {
            $rows[] = [
                $log->id,
                $log->obj_type,
                $log->obj_id,
                $log->body,
                $log->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
