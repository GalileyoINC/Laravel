<?php

declare(strict_types=1);

namespace App\Domain\Actions\InfluencerAssistant;

use App\Models\Subscription\InfluencerAssistant;

final class ExportInfluencerAssistantsToCsvAction
{
    public function execute(array $filters): array
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

        $items = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['Influencer', 'Assistant', 'Created At'];
        foreach ($items as $influencerAssistant) {
            $rows[] = [
                $influencerAssistant->influencer ? $influencerAssistant->influencer->first_name.' '.$influencerAssistant->influencer->last_name : '',
                $influencerAssistant->assistant ? $influencerAssistant->assistant->first_name.' '.$influencerAssistant->assistant->last_name : '',
                $influencerAssistant->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
