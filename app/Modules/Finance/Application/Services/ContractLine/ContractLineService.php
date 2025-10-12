<?php

declare(strict_types=1);

namespace App\Services\ContractLine;

use App\DTOs\ContractLine\ContractLineListRequestDTO;
use App\Models\Finance\ContractLine;

class ContractLineService implements ContractLineServiceInterface
{
    public function getUnpaidContracts(ContractLineListRequestDTO $dto): array
    {
        $query = ContractLine::query()
            ->with(['user', 'service'])
            ->whereNull('paid_at')
            ->where('status', '!=', 'cancelled');

        if ($dto->search) {
            $query->where(function ($q) use ($dto) {
                $q->whereHas('user', function ($userQuery) use ($dto) {
                    $userQuery->where('first_name', 'like', '%'.$dto->search.'%')
                        ->orWhere('last_name', 'like', '%'.$dto->search.'%')
                        ->orWhere('email', 'like', '%'.$dto->search.'%');
                });
            });
        }

        if ($dto->user_id) {
            $query->where('id_user', $dto->user_id);
        }

        if ($dto->service_id) {
            $query->where('id_service', $dto->service_id);
        }

        $contracts = $query->orderBy('created_at', 'desc')
            ->paginate($dto->limit, ['*'], 'page', $dto->page);

        return [
            'data' => $contracts->items(),
            'pagination' => [
                'current_page' => $contracts->currentPage(),
                'last_page' => $contracts->lastPage(),
                'per_page' => $contracts->perPage(),
                'total' => $contracts->total(),
            ],
        ];
    }
}
