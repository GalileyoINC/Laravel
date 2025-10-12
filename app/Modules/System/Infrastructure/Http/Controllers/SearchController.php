<?php

declare(strict_types=1);

namespace App\Modules\System\Infrastructure\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Search controller with DDD structure
 */
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
    public function index(SearchRequest $request): JsonResponse
    {
        try {
            // Request validation is handled automatically by SearchRequest
            $result = $this->searchAction->execute($request->validated());

            // Return the result directly since SearchAction already returns JsonResponse
            return $result;

        } catch (Exception $e) {
            // Use ErrorResource for consistent error format
            return response()->json(new ErrorResource([
                'message' => $e->getMessage(),
                'code' => 500,
                'trace_id' => uniqid(),
            ]), 500);
        }
    }
}
