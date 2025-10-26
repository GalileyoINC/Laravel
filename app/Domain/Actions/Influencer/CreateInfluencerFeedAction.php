<?php

declare(strict_types=1);

namespace App\Domain\Actions\Influencer;

use App\Domain\DTOs\Influencer\InfluencerFeedCreateRequestDTO;
use App\Domain\Services\Influencer\InfluencerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CreateInfluencerFeedAction
{
    public function __construct(
        private readonly InfluencerServiceInterface $influencerService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
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
    }
}
