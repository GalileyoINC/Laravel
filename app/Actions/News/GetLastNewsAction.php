<?php

namespace App\Actions\News;

use App\DTOs\News\NewsListRequestDTO;
use App\Services\News\NewsServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetLastNewsAction
{
    public function __construct(
        private NewsServiceInterface $newsService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = NewsListRequestDTO::fromArray($data);
            if (!$dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid news list request'],
                    'message' => 'Invalid request parameters'
                ], 400);
            }

            $user = Auth::user();
            $news = $this->newsService->getLastNews($dto, $user);

            return response()->json($news->toArray());

        } catch (\Exception $e) {
            Log::error('GetLastNewsAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
