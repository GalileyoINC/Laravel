<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Bundle\CreateBundleAction;
use App\Domain\Actions\Bundle\GetBundleDeviceDataAction;
use App\Domain\Actions\Bundle\GetBundleListAction;
use App\Domain\Actions\Bundle\UpdateBundleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bundle\BundleCreateRequest;
use App\Http\Requests\Bundle\BundleDeviceDataRequest;
use App\Http\Requests\Bundle\BundleListRequest;
use App\Http\Requests\Bundle\BundleUpdateRequest;
use App\Http\Resources\BundleResource;
use Illuminate\Http\JsonResponse;

class BundleController extends Controller
{
    public function index(BundleListRequest $request, GetBundleListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return BundleResource::collection($result)->response();
    }

    public function create(BundleCreateRequest $request, CreateBundleAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return BundleResource::make($result)->response()->setStatusCode(201);
    }

    public function update(int $id, BundleUpdateRequest $request, UpdateBundleAction $action): JsonResponse
    {
        $data = array_merge($request->validated(), ['id' => $id]);
        $result = $action->execute($data);

        return BundleResource::make($result)->response();
    }

    public function deviceData(BundleDeviceDataRequest $request, GetBundleDeviceDataAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json($result);
    }
}
