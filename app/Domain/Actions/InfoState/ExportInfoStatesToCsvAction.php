<?php

declare(strict_types=1);

namespace App\Domain\Actions\InfoState;

use App\Models\Analytics\InfoState;

final class ExportInfoStatesToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<list<mixed>>
     */
    public function execute(array $filters): array
    {
        $query = InfoState::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['key'])) {
            $query->where('key', 'like', "%{$filters['key']}%");
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }
        if (! empty($filters['updated_at_from'])) {
            $query->whereDate('updated_at', '>=', $filters['updated_at_from']);
        }
        if (! empty($filters['updated_at_to'])) {
            $query->whereDate('updated_at', '<=', $filters['updated_at_to']);
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Key', 'Value', 'Created At', 'Updated At'];
        foreach ($items as $infoState) {
            $rows[] = [
                $infoState->id,
                $infoState->key,
                is_array($infoState->value) ? json_encode($infoState->value) : $infoState->value,
                $infoState->created_at->format('Y-m-d H:i:s'),
                $infoState->updated_at?->format('Y-m-d H:i:s') ?? '',
            ];
        }

        return $rows;
    }
}
