<?php

declare(strict_types=1);

namespace App\Domain\Actions\Twilio;

use App\Models\System\TwilioCarrier;

final class ExportTwilioCarriersToCsvAction
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

        $items = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Name', 'Provider', 'Created At'];
        /** @var TwilioCarrier $carrier */
        foreach ($items as $carrier) {
            $rows[] = [
                $carrier->id,
                $carrier->name,
                $carrier->provider->name ?? '',
                $carrier->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
