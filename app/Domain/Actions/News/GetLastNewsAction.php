<?php

declare(strict_types=1);

namespace App\Domain\Actions\News;

use App\Domain\DTOs\News\NewsListRequestDTO;
use App\Domain\Services\News\NewsServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetLastNewsAction
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
            $news = $this->newsService->getLastNews($dto, $user);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'more_than_id' => null,
                    'less_than_id' => null,
                    'is_test_count' => null,
                    'list' => $news->map(fn ($item) => [
                        'id' => $item->id,
                        'type' => $item->type,
                        'title' => $item->title,
                        'subtitle' => $item->subtitle,
                        'body' => $item->body,
                        'image' => $item->image,
                        'emergency_level' => $item->emergency_level,
                        'location' => $item->location,
                        'is_liked' => $item->is_liked,
                        'is_bookmarked' => $item->is_bookmarked,
                        'is_subscribed' => $item->is_subscribed,
                        'is_owner' => $item->is_owner,
                        'comment_quantity' => $item->comment_quantity,
                        'created_at' => $item->created_at,
                        'images' => $item->images,
                        'reactions' => $item->reactions,
                        'percent' => $item->percent ?? null,
                        'price' => $item->price ?? null,
                    ])->toArray(),
                    'count' => $news->count(),
                    'page' => 1,
                    'page_size' => $news->count(),
                ],
            ]);

        } catch (Exception $e) {
            Log::error('GetLastNewsAction error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
