<?php

declare(strict_types=1);

namespace App\Domain\Actions\Finance;

use App\Domain\DTOs\Finance\TerminateContractDTO;
use App\Models\Finance\ContractLine;
use Illuminate\Http\JsonResponse;

class TerminateContractAction
{
    public function execute(TerminateContractDTO $dto): JsonResponse
    {
        $contractLine = ContractLine::findOrFail($dto->contractLineId);
        $contractLine->terminated_at = $dto->terminatedAt;
        $contractLine->save();

        return response()->json([
            'success' => true,
            'data' => $contractLine,
        ]);
    }
}
