<?php

declare(strict_types=1);

namespace App\Domain\Actions\Logs;

use App\Models\System\ActiveRecordLog;
use App\Models\System\Staff;
use App\Models\User\User;

final class ExportActiveRecordLogsToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<list<mixed>>
     */
    public function execute(array $filters): array
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

        $logs = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Created At', 'User', 'Staff', 'Action Type', 'Model', 'ID Model', 'Field', 'Changes'];
        /** @var ActiveRecordLog $log */
        foreach ($logs as $log) {
            /** @var ActiveRecordLog $log */
            $userName = '';
            if ($log->user) {
                /** @var User $user */
                $user = $log->user;
                $userName = trim($user->first_name.' '.$user->last_name)." ({$user->id})";
            }
            $staffName = '';
            if ($log->staff) {
                /** @var Staff $staff */
                $staff = $log->staff;
                $staffName = $staff->username." ({$staff->id})";
            }
            $rows[] = [
                $log->id,
                $log->created_at->format('Y-m-d H:i:s'),
                $userName,
                $staffName,
                $log->action_type,
                $log->model,
                $log->id_model,
                $log->field,
                json_encode($log->changes),
            ];
        }

        return $rows;
    }
}
