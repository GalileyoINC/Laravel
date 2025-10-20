<?php

declare(strict_types=1);

namespace App\Domain\Actions\InfluencerAssistant;

use App\Models\Subscription\InfluencerAssistant;

final class ExportInfluencerAssistantsToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<list<mixed>>
     */
    public function execute(array $filters): array
    {
        $query = InfluencerAssistant::with(['user']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        if (! empty($filters['userInfluencerName'])) {
            $name = (string) $filters['userInfluencerName'];
            $query->whereHas('user', function ($userQuery) use ($name) {
                $userQuery->where('first_name', 'like', "%{$name}%")
                    ->orWhere('last_name', 'like', "%{$name}%");
            });
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['User', 'Created At'];
        /** @var InfluencerAssistant $influencerAssistant */
        foreach ($items as $influencerAssistant) {
            $rows[] = [
                $influencerAssistant->user ? $influencerAssistant->user->first_name.' '.$influencerAssistant->user->last_name : '',
                $influencerAssistant->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
