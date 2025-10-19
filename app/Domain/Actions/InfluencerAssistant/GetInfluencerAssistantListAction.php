<?php

declare(strict_types=1);

namespace App\Domain\Actions\InfluencerAssistant;

use App\Models\Subscription\InfluencerAssistant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetInfluencerAssistantListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = InfluencerAssistant::with(['influencer', 'assistant']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('influencer', function ($userQuery) use ($search) {
                    $userQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhereHas('assistant', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['userInfluencerName'])) {
            $name = (string) $filters['userInfluencerName'];
            $query->whereHas('influencer', function ($userQuery) use ($name) {
                $userQuery->where('first_name', 'like', "%{$name}%")
                    ->orWhere('last_name', 'like', "%{$name}%");
            });
        }

        if (! empty($filters['userAssistantName'])) {
            $name = (string) $filters['userAssistantName'];
            $query->whereHas('assistant', function ($userQuery) use ($name) {
                $userQuery->where('first_name', 'like', "%{$name}%")
                    ->orWhere('last_name', 'like', "%{$name}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
