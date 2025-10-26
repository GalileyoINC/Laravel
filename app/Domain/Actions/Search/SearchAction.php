<?php

declare(strict_types=1);

namespace App\Domain\Actions\Search;

use App\Domain\DTOs\Search\SearchRequestDTO;
use App\Domain\Services\Search\SearchServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SearchAction
{
    public function __construct(
        private readonly SearchServiceInterface $searchService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $dto = SearchRequestDTO::fromArray($data);
        if (! $dto->validate()) {
            return response()->json([
                'status' => 'error',
                'errors' => ['Invalid search request'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $user = Auth::user();
        $results = $this->searchService->search($dto, $user);

        return response()->json([
            'status' => 'success',
            'data' => $results,
        ]);
    }
}
