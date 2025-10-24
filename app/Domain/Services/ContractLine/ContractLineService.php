<?php

declare(strict_types=1);

namespace App\Domain\Services\ContractLine;

use App\Models\Finance\ContractLine;

class ContractLineService implements ContractLineServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getUnpaidContracts(int $page, int $limit, ?string $search, ?int $userId, ?int $serviceId, string $status): array
    {
        $query = ContractLine::query()
            ->with(['user', 'service'])
            ->whereNull('paid_at')
            ->where('status', '!=', 'cancelled');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('first_name', 'like', '%'.$search.'%')
                        ->orWhere('last_name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            });
        }

        if ($userId) {
            $query->where('id_user', $userId);
        }

        if ($serviceId) {
            $query->where('id_service', $serviceId);
        }

        $contracts = $query->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

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
