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
    }

    public function view(int $id, GetUserDetailAction $action): JsonResponse
    {
        $user = $action->execute($id);

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    public function exportToCsv(): JsonResponse
    {
        $users = \App\Models\User\User::all();

        return response()->json([
            'status' => 'success',
            'data' => $users,
            'csv_available' => true,
        ]);
    }
}
