<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Device\DeleteDeviceAction;
use App\Domain\Actions\Device\GetDeviceAction;
use App\Domain\Actions\Device\GetDeviceListAction;
use App\Domain\Actions\Device\SendPushNotificationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Device\DeviceListRequest;
use App\Http\Requests\Device\DevicePushRequest;
use App\Http\Resources\DeviceResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Devices', description: 'Device management operations')]
class DeviceController extends Controller
{
    #[OA\Post(
        path: '/api/v1/device',
        description: 'Get list of devices',
        summary: 'List devices',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                    new OA\Property(property: 'search', type: 'string', example: 'device search'),
                ]
            )
        ),
        tags: ['Devices'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Devices retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(DeviceListRequest $request, GetDeviceListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return DeviceResource::collection($result)->response();
    }

    #[OA\Get(
        path: '/api/v1/device/{id}',
        description: 'Get specific device details',
        summary: 'View device',
        tags: ['Devices'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Device ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Device retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Device not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Device not found'),
                    ]
                )
            ),
        ]
    )]
    public function view(int $id, GetDeviceAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return DeviceResource::make($result)->response();
    }

    #[OA\Delete(
        path: '/api/v1/device/{id}',
        description: 'Delete a device',
        summary: 'Delete device',
        tags: ['Devices'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Device ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Device deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Device deleted successfully'),
                    ]
                )
            ),
        ]
    )]
    public function delete(int $id, DeleteDeviceAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Device deleted successfully',
        ]);
    }

    #[OA\Post(
        path: '/api/v1/device/{id}/push',
        description: 'Send push notification to device',
        summary: 'Send push notification',
        tags: ['Devices'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Device ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'message'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Notification Title'),
                    new OA\Property(property: 'message', type: 'string', example: 'Notification message content'),
                    new OA\Property(property: 'data', type: 'object', example: ['key' => 'value']),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Push notification sent successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Push notification sent successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function push(int $id, DevicePushRequest $request, SendPushNotificationAction $action): JsonResponse
    {
        $data = array_merge($request->validated(), ['id' => $id]);
        $result = $action->execute($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Push notification sent successfully',
            'data' => $result,
        ]);
    }
}
