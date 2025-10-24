<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Settings\FlushSettingsAction;
use App\Domain\Actions\Settings\GenerateBitpayConfigAction;
use App\Domain\Actions\Settings\GetPublicSettingsAction;
use App\Domain\Actions\Settings\GetSettingsAction;
use App\Domain\Actions\Settings\UpdateSettingsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\SettingsPublicRequest;
use App\Http\Requests\Settings\SettingsUpdateRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Settings', description: 'System settings management operations')]
class SettingsController extends Controller
{
    #[OA\Get(
        path: '/api/v1/settings',
        description: 'Get all system settings',
        summary: 'Get settings',
        tags: ['Settings'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Settings retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(GetSettingsAction $action): JsonResponse
    {
        $result = $action->execute([]);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    #[OA\Put(
        path: '/api/v1/settings',
        description: 'Update system settings',
        summary: 'Update settings',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'app_name', type: 'string', example: 'Galileyo'),
                    new OA\Property(property: 'app_url', type: 'string', example: 'https://galileyo.com'),
                    new OA\Property(property: 'mail_from_address', type: 'string', example: 'noreply@galileyo.com'),
                    new OA\Property(property: 'mail_from_name', type: 'string', example: 'Galileyo'),
                    new OA\Property(property: 'sms_enabled', type: 'boolean', example: true),
                    new OA\Property(property: 'push_notifications_enabled', type: 'boolean', example: true),
                ]
            )
        ),
        tags: ['Settings'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Settings updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Settings updated successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function update(SettingsUpdateRequest $request, UpdateSettingsAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Settings updated successfully',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/v1/settings/flush',
        description: 'Flush settings cache',
        summary: 'Flush settings',
        tags: ['Settings'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Settings cache flushed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Settings flushed successfully'),
                    ]
                )
            ),
        ]
    )]
    public function flush(FlushSettingsAction $action): JsonResponse
    {
        $result = $action->execute([]);

        return response()->json([
            'status' => 'success',
            'message' => 'Settings flushed successfully',
        ]);
    }

    #[OA\Post(
        path: '/api/v1/settings/public',
        description: 'Get public settings',
        summary: 'Get public settings',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'include_version', type: 'boolean', example: true),
                    new OA\Property(property: 'include_features', type: 'boolean', example: false),
                ]
            )
        ),
        tags: ['Settings'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Public settings retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function public(SettingsPublicRequest $request, GetPublicSettingsAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/v1/settings/bitpay-generation',
        description: 'Generate Bitpay configuration',
        summary: 'Generate Bitpay config',
        tags: ['Settings'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Bitpay configuration generated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function bitpayGeneration(GenerateBitpayConfigAction $action): JsonResponse
    {
        $result = $action->execute([]);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
