<?php

declare(strict_types=1);

namespace App\Domain\Actions\Service;

use App\Models\Finance\Service;

final class ExportServicesToCsvAction
{
    public function execute(array $filters): array
    {
        $query = Service::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }
        if (! empty($filters['price_from'])) {
            $query->where('price', '>=', $filters['price_from']);
        }
        if (! empty($filters['price_to'])) {
            $query->where('price', '<=', $filters['price_to']);
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', (int) $filters['is_active']);
        }
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        $services = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Name', 'Description', 'Price', 'Bonus Point', 'Type', 'Is Active', 'Created At', 'Updated At'];
        foreach ($services as $service) {
            $rows[] = [
                $service->id,
                $service->name,
                $service->description,
                $service->price,
                $service->bonus_point,
                $service->type,
                $service->is_active ? 'Yes' : 'No',
                $service->created_at->format('Y-m-d H:i:s'),
                $service->updated_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
