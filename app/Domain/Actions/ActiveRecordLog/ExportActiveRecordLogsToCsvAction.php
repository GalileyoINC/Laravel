<?php

declare(strict_types=1);

namespace App\Domain\Actions\ActiveRecordLog;

use App\Models\System\ActiveRecordLog;

final class ExportActiveRecordLogsToCsvAction
{
    public function execute(array $filters): array
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

        $logs = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Created At', 'User', 'Staff', 'Action Type', 'Model', 'ID Model', 'Field', 'Changes'];
        $actionTypes = ActiveRecordLog::getActionTypeList();
        foreach ($logs as $log) {
            $rows[] = [
                $log->id,
                $log->created_at->format('Y-m-d H:i:s'),
                $log->user ? $log->user->getFullName()." ({$log->user->id})" : '',
                $log->staff ? ($log->staff->username." ({$log->staff->id})") : '',
                $actionTypes[$log->action_type] ?? $log->action_type,
                $log->model,
                $log->id_model,
                $log->field,
                is_array($log->changes) ? json_encode($log->changes) : $log->changes,
            ];
        }

        return $rows;
    }
}
