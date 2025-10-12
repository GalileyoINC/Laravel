<?php

namespace App\Http\Controllers\Api;

use App\Actions\Search\SearchAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Search\SearchRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SearchResource;
use Illuminate\Http\JsonResponse;

/**
 * Search controller with DDD structure
 */
class SearchController extends Controller
{
    public function __construct(
        private SearchAction $searchAction
    ) {}

    /**
     * Search for content
     *
     * POST /api/search/index
     */
    public function index(SearchRequest $request): JsonResponse
    {
        try {
            // Request validation is handled automatically by SearchRequest
            $result = $this->searchAction->execute($request->validated());

            // Return the result directly since SearchAction already returns JsonResponse
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

