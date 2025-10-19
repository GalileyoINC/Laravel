<?php

declare(strict_types=1);

namespace App\Domain\Actions\Twilio;

use App\Models\System\TwilioCarrier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetTwilioCarrierListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = TwilioCarrier::with('provider');

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['provider_id'])) {
            $query->where('provider_id', (int) $filters['provider_id']);
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
