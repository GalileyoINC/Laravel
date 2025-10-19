<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Staff\CreateStaffAction;
use App\Domain\Actions\Staff\DeleteStaffAction;
use App\Domain\Actions\Staff\GetStaffAction;
use App\Domain\Actions\Staff\GetStaffListAction;
use App\Domain\Actions\Staff\UpdateStaffAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffCreateRequest;
use App\Http\Requests\Staff\StaffListRequest;
use App\Http\Requests\Staff\StaffUpdateRequest;
use App\Http\Resources\StaffResource;
use Illuminate\Http\JsonResponse;

class StaffController extends Controller
{
    public function index(StaffListRequest $request, GetStaffListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return StaffResource::collection($result)->response();
    }

    public function view(int $id, GetStaffAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return StaffResource::make($result)->response();
    }

    public function create(StaffCreateRequest $request, CreateStaffAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return StaffResource::make($result)->response()->setStatusCode(201);
    }

    public function update(int $id, StaffUpdateRequest $request, UpdateStaffAction $action): JsonResponse
    {
        $data = array_merge($request->validated(), ['id' => $id]);
        $result = $action->execute($data);

        return StaffResource::make($result)->response();
    }

    public function delete(int $id, DeleteStaffAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Staff deleted successfully',
        ]);
    }
}
