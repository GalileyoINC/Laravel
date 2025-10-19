<?php

declare(strict_types=1);

namespace App\Domain\Actions\Promocode;

use App\Models\Finance\Promocode;

final class ExportPromocodesToCsvAction
{
    public function execute(array $filters): array
    {
        $query = Promocode::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('text', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', (int) $filters['is_active']);
        }
        if (! empty($filters['active_from_from'])) {
            $query->whereDate('active_from', '>=', $filters['active_from_from']);
        }
        if (! empty($filters['active_from_to'])) {
            $query->whereDate('active_from', '<=', $filters['active_from_to']);
        }
        if (! empty($filters['active_to_from'])) {
            $query->whereDate('active_to', '>=', $filters['active_to_from']);
        }
        if (! empty($filters['active_to_to'])) {
            $query->whereDate('active_to', '<=', $filters['active_to_to']);
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Type', 'Text', 'Discount', 'Trial Period', 'Active From', 'Active To', 'Is Active', 'Show on Frontend', 'Description', 'Created At'];
        foreach ($items as $promocode) {
            $rows[] = [
                $promocode->id,
                ucfirst((string) $promocode->type),
                $promocode->text,
                $promocode->discount,
                $promocode->trial_period,
                $promocode->active_from ? $promocode->active_from->format('Y-m-d') : null,
                $promocode->active_to ? $promocode->active_to->format('Y-m-d') : null,
                $promocode->is_active ? 'Yes' : 'No',
                $promocode->show_on_frontend ? 'Yes' : 'No',
                $promocode->description,
                $promocode->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
