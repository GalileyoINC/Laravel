<?php

declare(strict_types=1);

namespace App\Actions\Influencer;

use App\DTOs\Influencer\InfluencerFeedCreateRequestDTO;
use App\Services\Influencer\InfluencerServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateInfluencerFeedAction
{
    public function __construct(
        private readonly InfluencerServiceInterface $influencerService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = InfluencerFeedCreateRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid influencer feed create request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $user = Auth::user();
            if (! $user || ! $user->is_influencer) {
                return response()->json([
                    'error' => 'Access denied. User must be an influencer.',
                    'code' => 403,
                ], 403);
            }

            $feed = $this->influencerService->createInfluencerFeed($dto, $user);

            return response()->json($feed->toArray());

        } catch (Exception $e) {
            Log::error('CreateInfluencerFeedAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
