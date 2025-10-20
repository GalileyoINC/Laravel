<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmergencyTipsRequest;

use App\Models\User\EmergencyTipsRequest;

final class ExportEmergencyTipsRequestsToCsvAction
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
        $query = EmergencyTipsRequest::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['first_name'])) {
            $query->where('first_name', 'like', "%{$filters['first_name']}%");
        }
        if (! empty($filters['email'])) {
            $query->where('email', 'like', "%{$filters['email']}%");
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'First Name', 'Email', 'Created At'];
        /** @var EmergencyTipsRequest $emergencyTipsRequest */
        foreach ($items as $emergencyTipsRequest) {
            $rows[] = [
                $emergencyTipsRequest->id,
                $emergencyTipsRequest->first_name,
                $emergencyTipsRequest->email,
                $emergencyTipsRequest->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
