<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Staff\CreateStaffAction;
use App\Actions\Staff\DeleteStaffAction;
use App\Actions\Staff\GetStaffAction;
use App\Actions\Staff\GetStaffListAction;
use App\Actions\Staff\UpdateStaffAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffCreateRequest;
use App\Http\Requests\Staff\StaffListRequest;
use App\Http\Requests\Staff\StaffUpdateRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\StaffResource;
use Exception;
use Illuminate\Http\JsonResponse;

class StaffController extends Controller
{
    public function index(StaffListRequest $request, GetStaffListAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return StaffResource::collection($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function view(int $id, GetStaffAction $action): JsonResponse
    {
        try {
            $result = $action->execute(['id' => $id]);

            return StaffResource::make($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function create(StaffCreateRequest $request, CreateStaffAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return StaffResource::make($result)->response()->setStatusCode(201);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function update(int $id, StaffUpdateRequest $request, UpdateStaffAction $action): JsonResponse
    {
        try {
            $data = array_merge($request->validated(), ['id' => $id]);
            $result = $action->execute($data);

            return StaffResource::make($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function delete(int $id, DeleteStaffAction $action): JsonResponse
    {
        try {
            $result = $action->execute(['id' => $id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Staff deleted successfully',
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }
}
