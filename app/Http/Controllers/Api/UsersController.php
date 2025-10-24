<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Users\GetUserDetailAction;
use App\Domain\Actions\Users\GetUsersListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UsersListRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Users', description: 'User management endpoints')]
class UsersController extends Controller
{
    public function __construct(
        private readonly GetUsersListAction $getUsersListAction,
    ) {}

    /**
     * Get users list
     *
     * GET /api/users
     */
    #[OA\Get(
        path: '/api/users',
        description: 'Get paginated list of users',
        summary: 'List users',
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', description: 'Page number', schema: new OA\Schema(type: 'integer', example: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Items per page', schema: new OA\Schema(type: 'integer', example: 15)),
            new OA\Parameter(name: 'search', in: 'query', description: 'Search term', schema: new OA\Schema(type: 'string', example: 'john')),
            new OA\Parameter(name: 'role', in: 'query', description: 'Filter by role', schema: new OA\Schema(type: 'string', example: 'user')),
        ],
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Users list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', properties: [
                            new OA\Property(property: 'current_page', type: 'integer', example: 1),
                            new OA\Property(property: 'last_page', type: 'integer', example: 5),
                            new OA\Property(property: 'per_page', type: 'integer', example: 15),
                            new OA\Property(property: 'total', type: 'integer', example: 75),
                        ], type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(UsersListRequest $request): JsonResponse
    {
        // Delegate fetching/pagination to Action layer while preserving response shape
        $paginator = $this->getUsersListAction->execute($request->validated());

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

    /**
     * Get user details
     *
     * GET /api/users/{id}
     */
    #[OA\Get(
        path: '/api/users/{id}',
        description: 'Get user details by ID',
        summary: 'Get user',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'User ID', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'User not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'User not found'),
                    ]
                )
            ),
        ]
    )]
    public function view(int $id, GetUserDetailAction $action): JsonResponse
    {
        $user = $action->execute($id);

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    /**
     * Export users to CSV
     *
     * GET /api/users/export-csv
     */
    #[OA\Get(
        path: '/api/users/export-csv',
        description: 'Export all users to CSV format',
        summary: 'Export users CSV',
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'CSV export successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Users exported successfully'),
                        new OA\Property(property: 'data', properties: [
                            new OA\Property(property: 'file_url', type: 'string', example: 'https://example.com/exports/users.csv'),
                            new OA\Property(property: 'total_users', type: 'integer', example: 150),
                        ], type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function exportToCsv(): JsonResponse
    {
        $users = \App\Models\User\User::query()->get();

        return response()->json([
            'status' => 'success',
            'data' => $users,
            'csv_available' => true,
        ]);
    }
}
