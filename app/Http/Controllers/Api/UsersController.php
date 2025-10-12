<?php

namespace App\Http\Controllers\Api;

use App\Actions\Users\GetUsersListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UsersListRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\UsersResource;
use Illuminate\Http\JsonResponse;

/**
 * Users controller with DDD structure
 *
 * Now controllers only handle HTTP concerns
 */
class UsersController extends Controller
{
    public function __construct(
        private GetUsersListAction $getUsersListAction
    ) {}

    /**
     * Get all users (admin only)
     *
     * GET /api/users
     */
    public function index(UsersListRequest $request): JsonResponse
    {
        try {
            // Request validation is handled automatically by UsersListRequest
            $result = $this->getUsersListAction->execute($request->validated());

            // Return the result directly since GetUsersListAction already returns JsonResponse
            return $result;

        } catch (\Exception $e) {
            // Use ErrorResource for consistent error format
            return response()->json(new ErrorResource([
                'message' => $e->getMessage(),
                'code' => 500,
                'trace_id' => uniqid(),
            ]), 500);
        }
    }
}
