<?php

declare(strict_types=1);

namespace App\Domain\Actions\Register;

use App\Models\User\Register;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetRegisterListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Register>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
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

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
