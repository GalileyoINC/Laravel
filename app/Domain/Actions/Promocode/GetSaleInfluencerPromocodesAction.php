<?php

declare(strict_types=1);

namespace App\Domain\Actions\Promocode;

use App\Models\Finance\Promocode;
use Illuminate\Http\JsonResponse;

class GetSaleInfluencerPromocodesAction
{
    public function execute(int $perPage = 20): JsonResponse
    {
        $promocodes = Promocode::query()
            ->with('promocode_influencer')
            ->whereHas('promocode_influencer')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $promocodes,
        ]);
    }
}
