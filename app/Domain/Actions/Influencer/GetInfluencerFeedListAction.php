<?php

declare(strict_types=1);

namespace App\Domain\Actions\Influencer;

use App\Domain\DTOs\Influencer\InfluencerFeedListRequestDTO;
use App\Domain\Services\Influencer\InfluencerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetInfluencerFeedListAction
{
    public function __construct(
        private readonly InfluencerServiceInterface $influencerService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
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
    }
}
