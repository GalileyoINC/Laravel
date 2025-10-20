<?php

declare(strict_types=1);

namespace App\Domain\Actions\Provider;

use App\Models\Finance\Provider;

final class ExportProvidersToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<list<mixed>>
     */
    public function execute(array $filters): array
    {
        $query = Provider::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }
        if (! empty($filters['email'])) {
            $query->where('email', 'like', "%{$filters['email']}%");
        }
        if (isset($filters['is_satellite'])) {
            $query->where('is_satellite', (int) $filters['is_satellite']);
        }
        if (! empty($filters['country'])) {
            $query->where('country', $filters['country']);
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        $providers = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Name', 'Email', 'Is Satellite', 'Country', 'Created At', 'Updated At'];
        foreach ($providers as $provider) {
            $rows[] = [
                $provider->id,
                $provider->name,
                $provider->email,
                $provider->is_satellite ? 'Yes' : 'No',
                $provider->country,
                $provider->created_at->format('Y-m-d H:i:s'),
                $provider->updated_at?->format('Y-m-d H:i:s') ?? '',
            ];
        }

        return $rows;
    }
}
