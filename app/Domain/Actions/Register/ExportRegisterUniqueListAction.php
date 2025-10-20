<?php

declare(strict_types=1);

namespace App\Domain\Actions\Register;

use App\Models\User\Register;

final class ExportRegisterUniqueListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<array<int, mixed>>
     */
    public function execute(array $filters): array
    {
        $query = Register::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }
        if (isset($filters['is_unfinished_signup'])) {
            $query->where('is_unfinished_signup', (int) $filters['is_unfinished_signup']);
        }
        if (! empty($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        $registers = $query->selectRaw('MIN(id) as min_id, email, first_name, last_name, MIN(created_at) as min_created_at')
            ->groupBy('email', 'first_name', 'last_name')
            ->orderBy('min_created_at', 'desc')
            ->get();

        $rows = [];
        $rows[] = ['ID', 'Email', 'First Name', 'Last Name', 'Created At'];
        foreach ($registers as $register) {
            $rows[] = [
                $register->min_id ?? '',
                $register->email,
                $register->first_name,
                $register->last_name,
                $register->min_created_at ?? '',
            ];
        }

        return $rows;
    }
}
