<?php

declare(strict_types=1);

namespace App\Actions\Influencer;

use App\DTOs\Influencer\InfluencerFeedListRequestDTO;
use App\Services\Influencer\InfluencerServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetInfluencerFeedListAction
{
    public function __construct(
        private readonly InfluencerServiceInterface $influencerService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = InfluencerFeedListRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['Invalid influencer feed list request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $feeds = $this->influencerService->getInfluencerFeeds($dto, $user);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'list' => $feeds->toArray(),
                    'count' => $feeds->count(),
                    'page' => 1,
                    'page_size' => $feeds->count(),
                ],
            ]);

        } catch (Exception $e) {
            Log::error('GetInfluencerFeedListAction error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
