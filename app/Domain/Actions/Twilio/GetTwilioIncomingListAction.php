<?php

declare(strict_types=1);

namespace App\Domain\Actions\Twilio;

use App\Models\System\TwilioIncoming;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetTwilioIncomingListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, TwilioIncoming>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = TwilioIncoming::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['number'])) {
            $query->where('number', 'like', "%{$filters['number']}%");
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
