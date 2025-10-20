<?php

declare(strict_types=1);

namespace App\Domain\Actions\IEX;

use App\Models\System\Setting;

final class ExportMarketstackIndexesToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<list<mixed>>
     */
    public function execute(array $filters): array
    {
        $query = Setting::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('prod', 'like', "%{$search}%")
                    ->orWhere('dev', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }

        $items = $query->orderBy('name')->get();

        $rows = [];
        $rows[] = ['Name', 'Prod', 'Dev'];
        foreach ($items as $setting) {
            $rows[] = [
                $setting->name,
                $setting->prod,
                $setting->dev,
            ];
        }

        return $rows;
    }
}
