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
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Staff', description: 'Staff management operations')]
class StaffController extends Controller
{
    #[OA\Post(
        path: '/api/v1/staff',
        description: 'Get list of staff members',
        summary: 'List staff',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                    new OA\Property(property: 'search', type: 'string', example: 'staff search'),
                    new OA\Property(property: 'role', type: 'string', example: 'admin'),
                ]
            )
        ),
        tags: ['Staff'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Staff members retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(StaffListRequest $request, GetStaffListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return StaffResource::collection($result)->response();
    }

    #[OA\Get(
        path: '/api/v1/staff/{id}',
        description: 'Get specific staff member details',
        summary: 'View staff member',
        tags: ['Staff'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Staff member ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Staff member retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Staff member not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Staff member not found'),
                    ]
                )
            ),
        ]
    )]
    public function view(int $id, GetStaffAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return StaffResource::make($result)->response();
    }

    #[OA\Post(
        path: '/api/v1/staff/create',
        description: 'Create new staff member',
        summary: 'Create staff member',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['first_name', 'last_name', 'email', 'role'],
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@galileyo.com'),
                    new OA\Property(property: 'role', type: 'string', example: 'admin'),
                    new OA\Property(property: 'phone', type: 'string', example: '+1234567890'),
                    new OA\Property(property: 'department', type: 'string', example: 'Engineering'),
                ]
            )
        ),
        tags: ['Staff'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Staff member created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function create(StaffCreateRequest $request, CreateStaffAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return StaffResource::make($result)->response()->setStatusCode(201);
    }

    #[OA\Put(
        path: '/api/v1/staff/{id}',
        description: 'Update staff member information',
        summary: 'Update staff member',
        tags: ['Staff'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Staff member ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@galileyo.com'),
                    new OA\Property(property: 'role', type: 'string', example: 'admin'),
                    new OA\Property(property: 'phone', type: 'string', example: '+1234567890'),
                    new OA\Property(property: 'department', type: 'string', example: 'Engineering'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Staff member updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function update(int $id, StaffUpdateRequest $request, UpdateStaffAction $action): JsonResponse
    {
        $data = array_merge($request->validated(), ['id' => $id]);
        $result = $action->execute($data);

        return StaffResource::make($result)->response();
    }

    #[OA\Delete(
        path: '/api/v1/staff/{id}',
        description: 'Delete staff member',
        summary: 'Delete staff member',
        tags: ['Staff'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Staff member ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Staff member deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Staff deleted successfully'),
                    ]
                )
            ),
        ]
    )]
    public function delete(int $id, DeleteStaffAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Staff deleted successfully',
        ]);
    }
}
