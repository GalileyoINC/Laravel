<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\CreditCard\GetCreditCardAction;
use App\Domain\Actions\CreditCard\GetCreditCardListAction;
use App\Domain\Actions\CreditCard\GetGatewayProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreditCard\CreditCardListRequest;
use App\Http\Resources\CreditCardResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Credit Cards', description: 'Credit card management operations')]
class CreditCardController extends Controller
{
    #[OA\Post(
        path: '/api/v1/credit-card',
        description: 'Get list of credit cards',
        summary: 'List credit cards',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                    new OA\Property(property: 'search', type: 'string', example: 'card search'),
                ]
            )
        ),
        tags: ['Credit Cards'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Credit cards retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(CreditCardListRequest $request, GetCreditCardListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return CreditCardResource::collection($result)->response();
    }

    #[OA\Get(
        path: '/api/v1/credit-card/{id}',
        description: 'Get specific credit card details',
        summary: 'View credit card',
        tags: ['Credit Cards'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Credit card ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Credit card retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Credit card not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Credit card not found'),
                    ]
                )
            ),
        ]
    )]
    public function view(int $id, GetCreditCardAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return CreditCardResource::make($result)->response();
    }

    #[OA\Get(
        path: '/api/v1/credit-card/{id}/gateway-profile',
        description: 'Get gateway profile for credit card',
        summary: 'Get gateway profile',
        tags: ['Credit Cards'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Credit card ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Gateway profile retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function getGatewayProfile(int $id, GetGatewayProfileAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
