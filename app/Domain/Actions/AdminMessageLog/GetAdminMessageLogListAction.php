<?php

declare(strict_types=1);

namespace App\Domain\Actions\AdminMessageLog;

use App\Models\System\AdminMessageLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetAdminMessageLogListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
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

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
