<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\EmailTemplate\GetEmailTemplateAction;
use App\Domain\Actions\EmailTemplate\GetEmailTemplateBodyAction;
use App\Domain\Actions\EmailTemplate\GetEmailTemplateListAction;
use App\Domain\Actions\EmailTemplate\SendAdminEmailAction;
use App\Domain\Actions\EmailTemplate\UpdateEmailTemplateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailTemplate\EmailTemplateListRequest;
use App\Http\Requests\EmailTemplate\EmailTemplateSendRequest;
use App\Http\Requests\EmailTemplate\EmailTemplateUpdateRequest;
use App\Http\Resources\EmailTemplateResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Email Templates', description: 'Email template management operations')]
class EmailTemplateController extends Controller
{
    #[OA\Post(
        path: '/api/v1/email-template',
        description: 'Get list of email templates',
        summary: 'List email templates',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                    new OA\Property(property: 'search', type: 'string', example: 'template search'),
                ]
            )
        ),
        tags: ['Email Templates'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email templates retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(EmailTemplateListRequest $request, GetEmailTemplateListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return EmailTemplateResource::collection($result)->response();
    }

    #[OA\Get(
        path: '/api/v1/email-template/{id}',
        description: 'Get specific email template details',
        summary: 'View email template',
        tags: ['Email Templates'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Email template ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email template retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Email template not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Email template not found'),
                    ]
                )
            ),
        ]
    )]
    public function view(int $id, GetEmailTemplateAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return EmailTemplateResource::make($result)->response();
    }

    #[OA\Put(
        path: '/api/v1/email-template/{id}',
        description: 'Update email template',
        summary: 'Update email template',
        tags: ['Email Templates'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Email template ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'subject', type: 'string', example: 'Updated Email Subject'),
                    new OA\Property(property: 'body', type: 'string', example: 'Updated email body content'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email template updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function update(int $id, EmailTemplateUpdateRequest $request, UpdateEmailTemplateAction $action): JsonResponse
    {
        $data = array_merge($request->validated(), ['id' => $id]);
        $result = $action->execute($data);

        return EmailTemplateResource::make($result)->response();
    }

    #[OA\Get(
        path: '/api/v1/email-template/{id}/body',
        description: 'Get email template body content',
        summary: 'View email template body',
        tags: ['Email Templates'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Email template ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email template body retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function viewBody(int $id, GetEmailTemplateBodyAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/v1/email-template/{id}/admin-send',
        description: 'Send email using template to admin',
        summary: 'Send admin email',
        tags: ['Email Templates'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Email template ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['recipient_email'],
                properties: [
                    new OA\Property(property: 'recipient_email', type: 'string', format: 'email', example: 'admin@example.com'),
                    new OA\Property(property: 'variables', type: 'object', example: ['name' => 'John Doe', 'company' => 'Example Corp']),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email sent successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Email sent successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function adminSend(int $id, EmailTemplateSendRequest $request, SendAdminEmailAction $action): JsonResponse
    {
        $data = array_merge($request->validated(), ['id' => $id]);
        $result = $action->execute($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Email sent successfully',
            'data' => $result,
        ]);
    }
}
