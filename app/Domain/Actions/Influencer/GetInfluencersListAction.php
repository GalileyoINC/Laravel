<?php

declare(strict_types=1);

namespace App\Domain\Actions\Influencer;

use App\Domain\DTOs\Influencer\InfluencersListRequestDTO;
use App\Domain\Services\Influencer\InfluencerServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Get influencers list action
 */
class GetInfluencersListAction
{
    public function __construct(
        private readonly InfluencerServiceInterface $influencerService
    ) {}

    /**
     * Execute get influencers list action
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        try {
            $dto = InfluencersListRequestDTO::fromArray($data);
            $result = $this->influencerService->getInfluencersList($dto);

            return response()->json([
                'status' => 'success',
                'data' => $result['data'],
                'count' => $result['count'],
            ]);

        } catch (Exception $e) {
            Log::error('GetInfluencersListAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'Failed to get influencers list',
                'code' => 500,
            ], 500);
        }
    }
}
