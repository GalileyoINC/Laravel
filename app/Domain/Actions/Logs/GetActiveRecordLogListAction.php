<?php

declare(strict_types=1);

namespace App\Domain\Actions\Logs;

use App\Models\System\ActiveRecordLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetActiveRecordLogListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = ActiveRecordLog::with(['user', 'staff']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('model', 'like', "%{$search}%")
                    ->orWhere('field', 'like', "%{$search}%")
                    ->orWhere('id_model', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('staff', function ($staffQuery) use ($search) {
                        $staffQuery->where('username', 'like', "%{$search}%");
                    });
            });
        }
        if (! empty($filters['action_type'])) {
            $query->where('action_type', $filters['action_type']);
        }
        if (! empty($filters['model'])) {
            $query->where('model', $filters['model']);
        }
        if (! empty($filters['id_user'])) {
            $query->where('id_user', (int) $filters['id_user']);
        }
        if (! empty($filters['id_staff'])) {
            $query->where('id_staff', (int) $filters['id_staff']);
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
