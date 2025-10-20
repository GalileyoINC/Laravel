<?php

declare(strict_types=1);

namespace App\Domain\Actions\CreditCard;

use App\Models\Finance\CreditCard;
use App\Models\User\User;

final class ExportCreditCardsToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<list<mixed>>
     */
    public function execute(array $filters): array
    {
        $query = CreditCard::with('user');

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('num', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('anet_customer_payment_profile_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (! empty($filters['expiration_year'])) {
            $query->where('expiration_year', $filters['expiration_year']);
        }
        if (! empty($filters['user_id'])) {
            $query->where('id_user', (int) $filters['user_id']);
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

        $creditCards = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'User ID', 'First Name', 'Last Name', 'Card Number', 'Phone', 'Type', 'Expiration', 'Is Active', 'Gateway Profile ID', 'Created At', 'Updated At'];
        foreach ($creditCards as $card) {
            /** @var CreditCard $card */
            $userName = '';
            $userLastName = '';
            if ($card->user) {
                /** @var User $user */
                $user = $card->user;
                $userName = $user->first_name ?? '';
                $userLastName = $user->last_name ?? '';
            }

            $rows[] = [
                $card->id,
                $card->id_user,
                $userName,
                $userLastName,
                $card->num,
                $card->phone,
                $card->type,
                $card->expiration_year.'/'.$card->expiration_month,
                $card->is_active ? 'Yes' : 'No',
                $card->anet_customer_payment_profile_id,
                $card->created_at->format('Y-m-d H:i:s'),
                $card->updated_at?->format('Y-m-d H:i:s') ?? '',
            ];
        }

        return $rows;
    }
}
