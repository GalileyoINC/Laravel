<?php

declare(strict_types=1);

namespace App\Domain\Actions\Podcast;

use App\Models\Content\Podcast;

final class ExportPodcastsToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, array<int, mixed>>
     */
    public function execute(array $filters): array
    {
        $query = Podcast::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        $podcasts = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Title', 'URL', 'Type', 'Image', 'Created At'];
        foreach ($podcasts as $podcast) {
            $rows[] = [
                $podcast->id,
                $podcast->title,
                $podcast->url,
                ucfirst((string) $podcast->type),
                $podcast->image ?: '-',
                $podcast->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
