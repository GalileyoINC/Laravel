<?php

declare(strict_types=1);

namespace App\Domain\Actions\ContractLine;

use App\Domain\DTOs\ContractLine\ContractLineListRequestDTO;
use App\Domain\Services\ContractLine\ContractLineServiceInterface;
use Exception;
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
        try {
            $dto = new ContractLineListRequestDTO(
                page: $data['page'] ?? 1,
                limit: $data['limit'] ?? 20,
                search: $data['search'] ?? null,
                user_id: $data['user_id'] ?? null,
                service_id: $data['service_id'] ?? null,
                status: $data['status'] ?? 'unpaid'
            );

            $contracts = $this->contractLineService->getUnpaidContracts($dto);

            return response()->json([
                'status' => 'success',
                'data' => $contracts,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get unpaid contracts: '.$e->getMessage(),
            ], 500);
        }
    }
}
