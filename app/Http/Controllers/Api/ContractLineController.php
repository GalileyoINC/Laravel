<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\ContractLine\GetUnpaidContractsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractLine\ContractLineListRequest;
use App\Http\Resources\ContractLineResource;
use Illuminate\Http\JsonResponse;

class ContractLineController extends Controller
{
    public function unpaid(ContractLineListRequest $request, GetUnpaidContractsAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return ContractLineResource::collection($result)->response();
    }
}
