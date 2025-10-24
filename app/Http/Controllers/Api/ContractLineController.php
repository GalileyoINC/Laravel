<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\ContractLine\GetUnpaidContractsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractLine\ContractLineListRequest;
use App\Http\Resources\ContractLineResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Contract Lines', description: 'Contract line management operations')]
class ContractLineController extends Controller
{
    #[OA\Post(
        path: '/api/v1/contract-line/unpaid',
        description: 'Get unpaid contract lines',
        summary: 'Get unpaid contracts',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                    new OA\Property(property: 'search', type: 'string', example: 'contract search'),
                ]
            )
        ),
        tags: ['Contract Lines'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Unpaid contracts retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Invalid request parameters'),
                    ]
                )
            ),
        ]
    )]
    public function unpaid(ContractLineListRequest $request, GetUnpaidContractsAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return ContractLineResource::collection($result)->response();
    }
}
