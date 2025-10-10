<?php

namespace App\Actions\Influencer;

use App\DTOs\Influencer\InfluencerFeedListRequestDTO;
use App\Services\Influencer\InfluencerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetInfluencerFeedListAction
{
    public function __construct(
        private InfluencerServiceInterface $influencerService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = InfluencerFeedListRequestDTO::fromArray($data);
            if (!$dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid influencer feed list request'],
                    'message' => 'Invalid request parameters'
                ], 400);
            }

            $user = Auth::user();
            if (!$user || !$user->is_influencer) {
                return response()->json([
                    'error' => 'Access denied. User must be an influencer.',
                    'code' => 403
                ], 403);
            }

            $feeds = $this->influencerService->getInfluencerFeeds($dto, $user);

            return response()->json($feeds->toArray());

        } catch (\Exception $e) {
            Log::error('GetInfluencerFeedListAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
