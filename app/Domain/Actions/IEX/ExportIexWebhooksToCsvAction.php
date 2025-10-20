<?php

declare(strict_types=1);

namespace App\Domain\Actions\IEX;

use App\Models\System\IexWebhook;

final class ExportIexWebhooksToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, array<int, mixed>>
     */
    /**
     * @return array<string, mixed>
     */
    public function execute(array $filters): array
    {
        $query = IexWebhook::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('iex_id', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('set', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['event'])) {
            $query->where('event', $filters['event']);
        }
        if (! empty($filters['set'])) {
            $query->where('set', $filters['set']);
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'IEX ID', 'Event', 'Set', 'Name', 'Created At', 'Updated At'];
        foreach ($items as $webhook) {
            $rows[] = [
                $webhook->id,
                $webhook->iex_id,
                $webhook->event,
                $webhook->set,
                $webhook->name,
                $webhook->created_at->format('Y-m-d H:i:s'),
                $webhook->updated_at?->format('Y-m-d H:i:s') ?? '',
            ];
        }

        return $rows;
    }
}
