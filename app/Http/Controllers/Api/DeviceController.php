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

class DeviceController extends Controller
{
    public function index(DeviceListRequest $request, GetDeviceListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return DeviceResource::collection($result)->response();
    }

    public function view(int $id, GetDeviceAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return DeviceResource::make($result)->response();
    }

    public function delete(int $id, DeleteDeviceAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Device deleted successfully',
        ]);
    }

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
