<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Search\SearchAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Search\SearchRequest;
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
        // Request validation is handled automatically by SearchRequest
        $result = $this->searchAction->execute($request->validated());

        // Return the result directly since SearchAction already returns JsonResponse
        return $result;
    }
}
