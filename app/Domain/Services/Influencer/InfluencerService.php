<?php

declare(strict_types=1);

namespace App\Domain\Services\Influencer;

use App\Domain\DTOs\Influencer\InfluencersListRequestDTO;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Influencer service interface
 */
interface InfluencerServiceInterface
{
    /**
     * Get influencers list
     *
     * @return array<string, mixed>
     */
    public function getInfluencersList(InfluencersListRequestDTO $dto): array;
}

/**
 * Influencer service implementation
 */
class InfluencerService implements InfluencerServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    public function getInfluencersList(InfluencersListRequestDTO $dto): array
    {
        try {
            $query = User::where('is_influencer', true)
                ->where('status', User::STATUS_ACTIVE);

            // Apply search filter
            if ($dto->search) {
                $query->where(function ($q) use ($dto) {
                    $q->where('first_name', 'like', '%'.$dto->search.'%')
                        ->orWhere('last_name', 'like', '%'.$dto->search.'%')
                        ->orWhere('email', 'like', '%'.$dto->search.'%');
                });
            }

            // Apply verified filter
            if ($dto->verifiedOnly) {
                $query->whereNotNull('influencer_verified_at');
            }

            $influencers = $query->select([
                'id',
                'email',
                'first_name',
                'last_name',
                'is_influencer',
                'influencer_verified_at',
                'created_at',
                'about',
                'avatar',
                'header_image',
            ])
                ->orderBy('created_at', 'desc')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            return [
                'data' => $influencers,
                'count' => $influencers->count(),
            ];

        } catch (Exception $e) {
            Log::error('InfluencerService getInfluencersList error: '.$e->getMessage());
            throw $e;
        }
    }
}
