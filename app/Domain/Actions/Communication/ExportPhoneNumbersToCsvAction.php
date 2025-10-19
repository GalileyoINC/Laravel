<?php

declare(strict_types=1);

namespace App\Domain\Actions\Communication;

use App\Models\Device\PhoneNumber;
use App\Models\User\User;

final class ExportPhoneNumbersToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, array<int, mixed>>
     */
    public function execute(array $filters): array
    {
        $query = PhoneNumber::with(['user']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                    ->orWhere('twilio_type', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }
        if (! empty($filters['userName'])) {
            $name = (string) $filters['userName'];
            $query->whereHas('user', function ($userQuery) use ($name) {
                $userQuery->where('first_name', 'like', "%{$name}%")
                    ->orWhere('last_name', 'like', "%{$name}%");
            });
        }
        foreach (['number', 'type', 'twilio_type'] as $field) {
            if (! empty($filters[$field])) {
                $query->where($field, 'like', "%{$filters[$field]}%");
            }
        }
        foreach (['is_valid', 'is_active', 'is_primary', 'is_send', 'id_provider'] as $field) {
            if (isset($filters[$field])) {
                $query->where($field, (int) $filters[$field]);
            }
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

        $items = $query->orderBy('updated_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'User', 'Email', 'Number', 'Valid', 'Type', 'Provider', 'Primary', 'Send', 'Created At', 'Updated At'];
        foreach ($items as $phoneNumber) {
            /** @var PhoneNumber $phoneNumber */
            $userName = '';
            $userEmail = '';
            if ($phoneNumber->user) {
                /** @var User $user */
                $user = $phoneNumber->user;
                $userName = $user->first_name.' '.$user->last_name;
                $userEmail = $user->email ?? '';
            }

            $rows[] = [
                $phoneNumber->id,
                $userName,
                $userEmail,
                $phoneNumber->number,
                $phoneNumber->is_valid ? 'Yes' : 'No',
                $phoneNumber->type ?? '',
                '',
                $phoneNumber->is_primary ? 'Yes' : 'No',
                $phoneNumber->is_send ? 'Yes' : 'No',
                $phoneNumber->created_at?->format('Y-m-d H:i:s') ?? '',
                $phoneNumber->updated_at?->format('Y-m-d H:i:s') ?? '',
            ];
        }

        return $rows;
    }
}
