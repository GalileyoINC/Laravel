<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\EmailPool\DeleteEmailPoolAction;
use App\Domain\Actions\EmailPool\GetEmailAttachmentAction;
use App\Domain\Actions\EmailPool\GetEmailPoolAction;
use App\Domain\Actions\EmailPool\GetEmailPoolListAction;
use App\Domain\Actions\EmailPool\ResendEmailAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailPool\EmailPoolListRequest;
use App\Http\Resources\EmailPoolResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Email Pool', description: 'Email pool management operations')]
class EmailPoolController extends Controller
{
    #[OA\Post(
        path: '/api/v1/email-pool',
        description: 'Get list of email pool entries',
        summary: 'List email pool',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                    new OA\Property(property: 'search', type: 'string', example: 'email search'),
                ]
            )
        ),
        tags: ['Email Pool'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email pool entries retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(EmailPoolListRequest $request, GetEmailPoolListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return EmailPoolResource::collection($result)->response();
    }

    #[OA\Get(
        path: '/api/v1/email-pool/{id}',
        description: 'Get specific email pool entry details',
        summary: 'View email pool entry',
        tags: ['Email Pool'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Email pool entry ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email pool entry retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Email pool entry not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Email pool entry not found'),
                    ]
                )
            ),
        ]
    )]
    public function view(int $id, GetEmailPoolAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return EmailPoolResource::make($result)->response();
    }

    #[OA\Delete(
        path: '/api/v1/email-pool/{id}',
        description: 'Delete an email pool entry',
        summary: 'Delete email pool entry',
        tags: ['Email Pool'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Email pool entry ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Email deleted successfully'),
                    ]
                )
            ),
        ]
    )]
    public function delete(int $id, DeleteEmailPoolAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Email deleted successfully',
        ]);
    }

    #[OA\Post(
        path: '/api/v1/email-pool/{id}/resend',
        description: 'Resend an email from the pool',
        summary: 'Resend email',
        tags: ['Email Pool'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Email pool entry ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email resent successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Email resent successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function resend(int $id, ResendEmailAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Email resent successfully',
            'data' => $result,
        ]);
    }

    #[OA\Get(
        path: '/api/v1/email-pool/{id}/attachment',
        description: 'Get email attachment details',
        summary: 'Get email attachment',
        tags: ['Email Pool'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Email pool entry ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email attachment retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function attachment(int $id, GetEmailAttachmentAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
