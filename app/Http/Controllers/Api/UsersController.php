<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UsersListRequest;
use App\Domain\Actions\Users\GetUsersListAction;
use Exception;
use App\Domain\Actions\Users\GetUserDetailAction;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    public function __construct(
        private readonly GetUsersListAction $getUsersListAction,
    ) {}

    public function index(UsersListRequest $request): JsonResponse
    {
        try {
            // Delegate fetching/pagination to Action layer while preserving response shape
            $paginator = $this->getUsersListAction->execute($request->all());

            return response()->json([
                'status' => 'success',
                'data' => $paginator->items(),
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function view(int $id, GetUserDetailAction $action): JsonResponse
    {
        try {
            $user = $action->execute($id);

            return response()->json([
                'status' => 'success',
                'data' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function exportToCsv(): JsonResponse
    {
        try {
            $users = \App\Models\User\User::all();

            return response()->json([
                'status' => 'success',
                'data' => $users,
                'csv_available' => true,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
