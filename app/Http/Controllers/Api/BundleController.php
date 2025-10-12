<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Bundle\CreateBundleAction;
use App\Actions\Bundle\GetBundleDeviceDataAction;
use App\Actions\Bundle\GetBundleListAction;
use App\Actions\Bundle\UpdateBundleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bundle\BundleCreateRequest;
use App\Http\Requests\Bundle\BundleDeviceDataRequest;
use App\Http\Requests\Bundle\BundleListRequest;
use App\Http\Requests\Bundle\BundleUpdateRequest;
use App\Http\Resources\BundleResource;
use App\Http\Resources\ErrorResource;
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
