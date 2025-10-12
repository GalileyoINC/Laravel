<?php

declare(strict_types=1);

namespace App\Modules\Device\Infrastructure\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;

class BundleController extends Controller
{
    public function index(BundleListRequest $request, GetBundleListAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return BundleResource::collection($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function create(BundleCreateRequest $request, CreateBundleAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return BundleResource::make($result)->response()->setStatusCode(201);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function update(int $id, BundleUpdateRequest $request, UpdateBundleAction $action): JsonResponse
    {
        try {
            $data = array_merge($request->validated(), ['id' => $id]);
            $result = $action->execute($data);

            return BundleResource::make($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function deviceData(BundleDeviceDataRequest $request, GetBundleDeviceDataAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return response()->json($result);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }
}
