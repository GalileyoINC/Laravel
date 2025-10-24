<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Search\SearchAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Search\SearchRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

/**
 * Search controller with DDD structure
 */
#[OA\Tag(name: 'Search', description: 'Search endpoints')]
class SearchController extends Controller
{
    public function __construct(
        private readonly SearchAction $searchAction
    ) {}

    /**
     * Search for content
     *
     * POST /api/search/index
     */
    #[OA\Post(
        path: '/api/v1/search/index',
        description: 'Search for content across the platform',
        summary: 'Search content',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['query'],
                properties: [
                    new OA\Property(property: 'query', type: 'string', example: 'search term'),
                    new OA\Property(property: 'type', type: 'string', example: 'all', enum: ['all', 'users', 'posts', 'comments']),
                    new OA\Property(property: 'limit', type: 'integer', example: 10),
                    new OA\Property(property: 'offset', type: 'integer', example: 0),
                ]
            )
        ),
        tags: ['Search'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Search results',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                        new OA\Property(property: 'meta', properties: [
                            new OA\Property(property: 'total', type: 'integer', example: 25),
                            new OA\Property(property: 'limit', type: 'integer', example: 10),
                            new OA\Property(property: 'offset', type: 'integer', example: 0),
                        ], type: 'object'),
                    ]
                )
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
    public function index(SearchRequest $request): JsonResponse
    {
        // Request validation is handled automatically by SearchRequest
        $result = $this->searchAction->execute($request->validated());

        // Return the result directly since SearchAction already returns JsonResponse
        return $result;
    }
}
