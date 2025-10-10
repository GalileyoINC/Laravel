<?php

namespace App\Actions\PrivateFeed;

use App\DTOs\PrivateFeed\PrivateFeedListRequestDTO;
use App\Services\PrivateFeed\PrivateFeedServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetPrivateFeedListAction
{
    public function __construct(
        private PrivateFeedServiceInterface $privateFeedService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = PrivateFeedListRequestDTO::fromArray($data);
            if (!$dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid private feed list request'],
                    'message' => 'Invalid request parameters'
                ], 400);
            }

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $privateFeeds = $this->privateFeedService->getPrivateFeedList($dto, $user);

            return response()->json($privateFeeds);

        } catch (\Exception $e) {
            Log::error('GetPrivateFeedListAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
