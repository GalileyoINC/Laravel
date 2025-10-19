<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Domain\DTOs\Users\ExportUsersRequestDTO;
use App\Models\User\User;

class ExportUsersToCsvAction
{
    public function execute(ExportUsersRequestDTO $dto): array
    {
        $query = User::with(['phoneNumbers', 'subscriptionContractLines', 'preAdmin']);

        if (! empty($dto->search)) {
            $search = $dto->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($dto->status !== null) {
            $query->where('status', $dto->status);
        }
        // role/validEmailOnly placeholders for future extension

        $users = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['Id', 'First Name', 'Last Name', 'Contact', 'Email', 'Phone', 'Type', 'Valid', 'Status', 'Influencer', 'Test', 'Active Plan', 'Plan Type', 'Refer', 'Created At', 'SPS', 'State', 'Country', 'Zip'];

        foreach ($users as $user) {
            $phone = $user->phoneNumbers->first();
            $phoneNumber = $phone ? $phone->number : '';
            $phoneType = $phone ? $phone->getFullTypeName() : '';
            $isValid = $phone ? ($phone->is_valid ? 'Yes' : 'No') : '';

            $status = ((int) $user->status) === 1 ? 'Active' : 'Cancelled';
            $influencer = $user->is_influencer ? 'Yes' : 'No';
            $test = $user->is_test ? 'Yes' : 'No';
            $spsActive = $user->is_sps_active ? 'Yes' : 'No';

            $activePlan = '';
            if ($user->preAdmin) {
                $activePlan = 'Subaccount';
            } elseif ($user->subscriptionContractLines->isNotEmpty()) {
                $activePlan = $user->subscriptionContractLines->pluck('title')->join('/');
            }

            $planType = '';
            if ($user->subscriptionContractLines->isNotEmpty()) {
                $planType = $user->subscriptionContractLines->map(function ($line) {
                    return $line->getPayIntervalString();
                })->join('/');
            }

            $rows[] = [
                $user->id,
                $user->first_name,
                $user->last_name,
                '',
                $user->email,
                $phoneNumber,
                $phoneType,
                $isValid,
                $status,
                $influencer,
                $test,
                $activePlan,
                $planType,
                $user->refer_type ?? '',
                $user->created_at->format('Y-m-d H:i:s'),
                $spsActive,
                $user->state ?? '',
                $user->country ?? '',
                $user->zip ?? '',
            ];
        }

        return $rows;
    }
}
