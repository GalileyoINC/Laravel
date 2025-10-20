<?php

declare(strict_types=1);

namespace App\Domain\Actions\Register;

use App\Models\User\Register;

final class ExportRegisterListAction
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

        $registers = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Email', 'First Name', 'Last Name', 'Created At'];
        foreach ($registers as $register) {
            $rows[] = [
                $register->id,
                $register->email,
                $register->first_name,
                $register->last_name,
                $register->created_at->toDateTimeString(),
            ];
        }

        return $rows;
    }
}
