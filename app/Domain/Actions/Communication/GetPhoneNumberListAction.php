<?php

declare(strict_types=1);

namespace App\Domain\Actions\Communication;

use App\Models\Device\PhoneNumber;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetPhoneNumberListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, PhoneNumber>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = PhoneNumber::with(['user']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }
        if (! empty($filters['userName'])) {
            $name = (string) $filters['userName'];
            $query->whereHas('user', function ($userQuery) use ($name) {
                $userQuery->where('first_name', 'like', "%{$name}%")
                    ->orWhere('last_name', 'like', "%{$name}%");
            });
        }
        foreach (['phone_number'] as $field) {
            if (! empty($filters[$field])) {
                $query->where($field, 'like', "%{$filters[$field]}%");
            }
        }
        foreach (['is_valid', 'is_active', 'is_primary', 'is_send'] as $field) {
            if (isset($filters[$field])) {
                $query->where($field, (int) $filters[$field]);
            }
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

        return $query->orderBy('updated_at', 'desc')->paginate($perPage);
    }
}
