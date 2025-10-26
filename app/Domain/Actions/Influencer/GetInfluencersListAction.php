<?php

declare(strict_types=1);

namespace App\Domain\Actions\Influencer;

use App\Domain\DTOs\Influencer\InfluencersListRequestDTO;
use App\Domain\Services\Influencer\InfluencerServiceInterface;
use Illuminate\Http\JsonResponse;

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
        $dto = InfluencersListRequestDTO::fromArray($data);
        $result = $this->influencerService->getInfluencersList($dto);

        return response()->json([
            'status' => 'success',
            'data' => $result['data'],
            'count' => $result['count'],
        ]);
    }
}
