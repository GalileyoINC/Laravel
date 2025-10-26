<?php

declare(strict_types=1);

namespace App\Domain\Actions\News;

use App\Domain\DTOs\News\NewsListRequestDTO;
use App\Domain\Services\News\NewsServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetNewsByInfluencersAction
{
    public function __construct(
        private readonly NewsServiceInterface $newsService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $dto = NewsListRequestDTO::fromArray($data);
        if (! $dto->validate()) {
            return response()->json([
                'status' => 'error',
                'errors' => ['Invalid news list request'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $user = Auth::user();
        $news = $this->newsService->getNewsByInfluencers($dto, $user);

        return response()->json([
            'status' => 'success',
            'data' => [
                'more_than_id' => null,
                'less_than_id' => null,
                'is_test_count' => null,
                'list' => $news->toArray(),
                'count' => $news->count(),
                'page' => 1,
                'page_size' => $news->count(),
            ],
        ]);
    }
}
