<?php

namespace App\Actions\News;

use App\DTOs\News\GetNewsByFollowerListRequestDTO;
use App\Services\News\NewsServiceInterface;
use App\Http\Resources\NewsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetNewsByFollowerListAction
{
    public function __construct(
        private NewsServiceInterface $newsService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = GetNewsByFollowerListRequestDTO::fromArray($data);
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $result = $this->newsService->getNewsByFollowerList($dto, $user);

            return response()->json(new NewsResource($result));

        } catch (\Exception $e) {
            Log::error('GetNewsByFollowerListAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
