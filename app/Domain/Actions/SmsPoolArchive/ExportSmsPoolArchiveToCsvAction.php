<?php

declare(strict_types=1);

namespace App\Domain\Actions\SmsPoolArchive;

use App\Models\Communication\SmsPool;
use App\Models\Communication\SmsPoolArchive;
use App\Models\System\Staff;
use App\Models\User\User;

final class ExportSmsPoolArchiveToCsvAction
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
        $query = SmsPoolArchive::with(['user', 'staff', 'subscription', 'followerList']);

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
        if (! empty($filters['id_subscription'])) {
            $query->where('id_subscription', (int) $filters['id_subscription']);
        }
        if (! empty($filters['followerListName'])) {
            $name = (string) $filters['followerListName'];
            $query->whereHas('followerList', function ($followerListQuery) use ($name) {
                $followerListQuery->where('name', 'like', "%{$name}%");
            });
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
        $rows[] = ['ID', 'Purpose', 'Subscription', 'Private Feed', 'Sender', 'Body', 'Created At', 'Updated At'];
        foreach ($items as $smsPoolArchive) {
            /** @var SmsPoolArchive $smsPoolArchive */
            $sender = '';
            if ($smsPoolArchive->user) {
                /** @var User $user */
                $user = $smsPoolArchive->user;
                $sender = 'User: '.$user->first_name.' '.$user->last_name;
            } elseif ($smsPoolArchive->staff) {
                /** @var Staff $staff */
                $staff = $smsPoolArchive->staff;
                $sender = 'Staff: '.$staff->username;
            }

            $rows[] = [
                $smsPoolArchive->id,
                SmsPool::getPurposes()[$smsPoolArchive->purpose] ?? $smsPoolArchive->purpose,
                $smsPoolArchive->subscription ? $smsPoolArchive->subscription->name : '',
                $smsPoolArchive->followerList ? $smsPoolArchive->followerList->name : '',
                $sender,
                $smsPoolArchive->body,
                $smsPoolArchive->created_at,
                $smsPoolArchive->updated_at ?? '',
            ];
        }

        return $rows;
    }
}
