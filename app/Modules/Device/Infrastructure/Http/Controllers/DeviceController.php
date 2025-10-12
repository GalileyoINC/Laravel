<?php

declare(strict_types=1);

namespace App\Modules\Device\Infrastructure\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    public function index(DeviceListRequest $request, GetDeviceListAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return DeviceResource::collection($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function view(int $id, GetDeviceAction $action): JsonResponse
    {
        try {
            $result = $action->execute(['id' => $id]);

            return DeviceResource::make($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function delete(int $id, DeleteDeviceAction $action): JsonResponse
    {
        try {
            $result = $action->execute(['id' => $id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Device deleted successfully',
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function push(int $id, DevicePushRequest $request, SendPushNotificationAction $action): JsonResponse
    {
        try {
            $data = array_merge($request->validated(), ['id' => $id]);
            $result = $action->execute($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Push notification sent successfully',
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }
}
