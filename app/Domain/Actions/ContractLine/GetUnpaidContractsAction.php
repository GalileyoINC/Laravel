<?php

declare(strict_types=1);

namespace App\Domain\Actions\ContractLine;

use App\Domain\Services\ContractLine\ContractLineServiceInterface;
use Illuminate\Http\JsonResponse;

class GetUnpaidContractsAction
{
    public function __construct(
        private readonly ContractLineServiceInterface $contractLineService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $page = $data['page'] ?? 1;
        $limit = $data['limit'] ?? 20;
        $search = $data['search'] ?? null;
        $userId = $data['user_id'] ?? null;
        $serviceId = $data['service_id'] ?? null;
        $status = $data['status'] ?? 'unpaid';

        $contracts = $this->contractLineService->getUnpaidContracts($page, $limit, $search, $userId, $serviceId, $status);

        return response()->json([
            'status' => 'success',
            'data' => $contracts,
        ]);
    }
}
