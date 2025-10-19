<?php

declare(strict_types=1);

namespace App\Domain\Actions\Finance;

use App\Domain\DTOs\Finance\ContractInvoiceLinesDTO;
use App\Models\Finance\InvoiceLine;
use Illuminate\Http\JsonResponse;

class GetPaidInvoiceLinesForContractAction
{
    public function execute(ContractInvoiceLinesDTO $dto): JsonResponse
    {
        $invoiceLines = InvoiceLine::with('invoice')
            ->where('id_contract_line', $dto->contractLineId)
            ->whereHas('invoice', function ($query) {
                $query->where('paid_status', true);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $invoiceLines,
        ]);
    }
}
