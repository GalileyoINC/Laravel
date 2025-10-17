<?php

declare(strict_types=1);

namespace App\Domain\Services\CreditCard;

use App\Domain\DTOs\CreditCard\CreditCardListRequestDTO;
use App\Models\Finance\CreditCard;

class CreditCardService implements CreditCardServiceInterface
{
    public function getList(CreditCardListRequestDTO $dto): array
    {
        $query = CreditCard::query()->with(['user']);

        if ($dto->search) {
            $query->where(function ($q) use ($dto) {
                $q->where('card_number', 'like', '%'.$dto->search.'%')
                    ->orWhere('cardholder_name', 'like', '%'.$dto->search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($dto) {
                        $userQuery->where('first_name', 'like', '%'.$dto->search.'%')
                            ->orWhere('last_name', 'like', '%'.$dto->search.'%')
                            ->orWhere('email', 'like', '%'.$dto->search.'%');
                    });
            });
        }

        if ($dto->user_id) {
            $query->where('id_user', $dto->user_id);
        }

        if ($dto->is_active !== null) {
            $query->where('is_active', $dto->is_active);
        }

        $creditCards = $query->orderBy('created_at', 'desc')
            ->paginate($dto->limit, ['*'], 'page', $dto->page);

        return [
            'data' => $creditCards->items(),
            'pagination' => [
                'current_page' => $creditCards->currentPage(),
                'last_page' => $creditCards->lastPage(),
                'per_page' => $creditCards->perPage(),
                'total' => $creditCards->total(),
            ],
        ];
    }

    public function getById(int $id): CreditCard
    {
        return CreditCard::with(['user'])->findOrFail($id);
    }

    public function getGatewayProfile(int $id): array
    {
        $creditCard = CreditCard::with(['user'])->findOrFail($id);

        // Mock gateway profile data - replace with actual gateway integration
        return [
            'credit_card' => [
                'id' => $creditCard->id,
                'card_number' => $creditCard->card_number,
                'cardholder_name' => $creditCard->cardholder_name,
                'expiry_month' => $creditCard->expiry_month,
                'expiry_year' => $creditCard->expiry_year,
                'is_active' => $creditCard->is_active,
            ],
            'user' => [
                'id' => $creditCard->user->id,
                'first_name' => $creditCard->user->first_name,
                'last_name' => $creditCard->user->last_name,
                'email' => $creditCard->user->email,
            ],
            'gateway_profile' => [
                'profile_id' => 'mock_profile_'.$creditCard->id,
                'status' => 'active',
                'created_at' => $creditCard->created_at,
                'updated_at' => $creditCard->updated_at,
            ],
        ];
    }
}
