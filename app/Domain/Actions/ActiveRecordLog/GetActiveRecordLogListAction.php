<?php

declare(strict_types=1);

namespace App\Domain\Actions\ActiveRecordLog;

use App\Models\System\ActiveRecordLog;
use App\Models\System\Staff;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetActiveRecordLogListAction
{
    /**
     * Returns paginated logs and supporting dropdown data.
     *
     * @param  array<string, mixed>  $filters
     * @return array{logs: LengthAwarePaginator<int, ActiveRecordLog>, actionTypes: array<int,string>, staffList: array<int,string>}
     */
    public function execute(array $filters, int $perPage = 20): array
    {
        $query = ActiveRecordLog::with(['user', 'staff']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('model', 'like', "%{$search}%")
                    ->orWhere('field', 'like', "%{$search}%")
                    ->orWhere('id_model', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['userName'])) {
            $userName = (string) $filters['userName'];
            $query->whereHas('user', function ($q) use ($userName) {
                $q->where('first_name', 'like', "%{$userName}%")
                    ->orWhere('last_name', 'like', "%{$userName}%")
                    ->orWhere('email', 'like', "%{$userName}%");
            });
        }
        if (! empty($filters['staffName'])) {
            $staffName = (string) $filters['staffName'];
            $query->whereHas('staff', function ($q) use ($staffName) {
                $q->where('username', 'like', "%{$staffName}%");
            });
        }
        if (! empty($filters['action_type'])) {
            $query->where('action_type', $filters['action_type']);
        }
        if (! empty($filters['model'])) {
            $query->where('model', 'like', "%{$filters['model']}%");
        }
        if (! empty($filters['field'])) {
            $query->where('field', 'like', "%{$filters['field']}%");
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate($perPage);
        $actionTypes = ActiveRecordLog::getActionTypeList();
        $staffList = Staff::getForDropDown();

        return [
            'logs' => $logs,
            'actionTypes' => $actionTypes,
            'staffList' => $staffList,
        ];
    }
}
