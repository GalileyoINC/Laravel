<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UsersListRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    public function index(UsersListRequest $request): JsonResponse
    {
        try {
            $usersQuery = \App\Models\User::query();

            if ($request->has('search')) {
                $usersQuery->where(function ($q) use ($request) {
                    $q->where('first_name', 'like', '%'.$request->search.'%')
                        ->orWhere('last_name', 'like', '%'.$request->search.'%')
                        ->orWhere('email', 'like', '%'.$request->search.'%');
                });
            }

            $users = $usersQuery->paginate($request->limit ?? 20);

            return response()->json([
                'status' => 'success',
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function view(int $id): JsonResponse
    {
        try {
            $user = \App\Models\User::findOrFail($id);

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
            $users = \App\Models\User::all();

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
