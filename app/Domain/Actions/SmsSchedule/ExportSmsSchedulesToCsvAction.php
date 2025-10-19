<?php

declare(strict_types=1);

namespace App\Domain\Actions\SmsSchedule;

use App\Models\Communication\SmsPool;
use App\Models\Communication\SmsShedule as SmsSchedule;
use App\Models\Subscription\Subscription;
use App\Models\System\Staff;
use App\Models\User\User;

final class ExportSmsSchedulesToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, array<int, mixed>>
     */
    public function execute(array $filters): array
    {
        $query = SmsSchedule::with(['user', 'staff', 'subscription', 'followerList', 'smsPool']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('body', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('staff', function ($staffQuery) use ($search) {
                        $staffQuery->where('username', 'like', "%{$search}%");
                    });
            });
        }
        if (! empty($filters['purpose'])) {
            $query->where('purpose', $filters['purpose']);
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['id_subscription'])) {
            $query->where('id_subscription', (int) $filters['id_subscription']);
        }
        if (! empty($filters['followerListName'])) {
            $name = (string) $filters['followerListName'];
            $query->whereHas('followerList', function ($followerListQuery) use ($name) {
                $followerListQuery->where('name', 'like', "%{$name}%");
            });
        }
        if (! empty($filters['sended_at_from'])) {
            $query->whereDate('sended_at', '>=', $filters['sended_at_from']);
        }
        if (! empty($filters['sended_at_to'])) {
            $query->whereDate('sended_at', '<=', $filters['sended_at_to']);
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

        $items = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Purpose', 'Sender', 'Subscription', 'Private Feed', 'Status', 'Body', 'Sended At', 'Created At', 'Updated At'];
        foreach ($items as $smsSchedule) {
            /** @var SmsSchedule $smsSchedule */
            $sender = '';
            if ($smsSchedule->user) {
                /** @var User $user */
                $user = $smsSchedule->user;
                $sender = 'User: '.$user->first_name.' '.$user->last_name;
            } elseif ($smsSchedule->staff) {
                /** @var Staff $staff */
                $staff = $smsSchedule->staff;
                $sender = 'Staff: '.$staff->username;
            }

            $subscriptionName = '';
            if ($smsSchedule->subscription) {
                /** @var Subscription $subscription */
                $subscription = $smsSchedule->subscription;
                $subscriptionName = $subscription->name ?? '';
            }

            $rows[] = [
                $smsSchedule->id,
                SmsPool::getPurposes()[$smsSchedule->purpose] ?? $smsSchedule->purpose,
                $sender,
                $subscriptionName,
                $smsSchedule->followerList ? ($smsSchedule->followerList->name ?? '') : '',
                SmsSchedule::getStatuses()[$smsSchedule->status] ?? $smsSchedule->status,
                $smsSchedule->body,
                $smsSchedule->sended_at ? $smsSchedule->sended_at->format('Y-m-d H:i:s') : '',
                $smsSchedule->created_at->format('Y-m-d H:i:s'),
                $smsSchedule->updated_at ? $smsSchedule->updated_at->format('Y-m-d H:i:s') : '',
            ];
        }

        return $rows;
    }
}
