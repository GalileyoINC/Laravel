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
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Bundles', description: 'Bundle management endpoints')]
class BundleController extends Controller
{
    /**
     * Get bundles list
     *
     * GET /api/v1/bundle/index
     */
    #[OA\Get(
        path: '/api/v1/bundle/index',
        description: 'Get paginated list of bundles',
        summary: 'List bundles',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', description: 'Page number', schema: new OA\Schema(type: 'integer', example: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Items per page', schema: new OA\Schema(type: 'integer', example: 15)),
            new OA\Parameter(name: 'status', in: 'query', description: 'Filter by status', schema: new OA\Schema(type: 'string', example: 'active')),
        ],
        tags: ['Bundles'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Bundles list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'meta', properties: [
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
    public function index(BundleListRequest $request, GetBundleListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return BundleResource::collection($result)->response();
    }

    /**
     * Create bundle
     *
     * POST /api/v1/bundle/create
     */
    #[OA\Post(
        path: '/api/v1/bundle/create',
        description: 'Create a new bundle',
        summary: 'Create bundle',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'description'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Premium Bundle'),
                    new OA\Property(property: 'description', type: 'string', example: 'Premium bundle with all features'),
                    new OA\Property(property: 'price', type: 'number', format: 'float', example: 99.99),
                    new OA\Property(property: 'features', type: 'array', items: new OA\Items(type: 'string'), example: ['feature1', 'feature2']),
                ]
            )
        ),
        tags: ['Bundles'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Bundle created successfully',
                content: new OA\JsonContent(type: 'object')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function create(BundleCreateRequest $request, CreateBundleAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return BundleResource::make($result)->response()->setStatusCode(201);
    }

    /**
     * Update bundle
     *
     * PUT /api/v1/bundle/update/{id}
     */
    #[OA\Put(
        path: '/api/v1/bundle/update/{id}',
        description: 'Update bundle by ID',
        summary: 'Update bundle',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'Bundle ID', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Updated Bundle'),
                    new OA\Property(property: 'description', type: 'string', example: 'Updated description'),
                    new OA\Property(property: 'price', type: 'number', format: 'float', example: 149.99),
                    new OA\Property(property: 'status', type: 'string', example: 'active', enum: ['active', 'inactive']),
                ]
            )
        ),
        tags: ['Bundles'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Bundle updated successfully',
                content: new OA\JsonContent(type: 'object')
            ),
            new OA\Response(
                response: 404,
                description: 'Bundle not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Bundle not found'),
                    ]
                )
            ),
        ]
    )]
    public function update(int $id, BundleUpdateRequest $request, UpdateBundleAction $action): JsonResponse
    {
        $data = array_merge($request->validated(), ['id' => $id]);
        $result = $action->execute($data);

        return BundleResource::make($result)->response();
    }

    /**
     * Get bundle device data
     *
     * POST /api/v1/bundle/device-data
     */
    #[OA\Post(
        path: '/api/v1/bundle/device-data',
        description: 'Get device data for a bundle',
        summary: 'Get bundle device data',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['bundle_id'],
                properties: [
                    new OA\Property(property: 'bundle_id', type: 'integer', example: 1, description: 'Bundle ID'),
                    new OA\Property(property: 'device_type', type: 'string', example: 'satellite', enum: ['satellite', 'mobile', 'web']),
                ]
            )
        ),
        tags: ['Bundles'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Device data retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Bundle not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Bundle not found'),
                    ]
                )
            ),
        ]
    )]
    public function deviceData(BundleDeviceDataRequest $request, GetBundleDeviceDataAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json($result);
    }
}
