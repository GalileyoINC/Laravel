<?php

declare(strict_types=1);

namespace App\Actions\News;

use App\DTOs\News\NewsListRequestDTO;
use App\Services\News\NewsServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetNewsByInfluencersAction
{
    public function __construct(
        private readonly NewsServiceInterface $newsService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
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

        } catch (Exception $e) {
            Log::error('GetNewsByInfluencersAction error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
