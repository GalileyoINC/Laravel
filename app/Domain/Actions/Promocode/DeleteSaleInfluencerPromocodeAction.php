<?php

declare(strict_types=1);

namespace App\Domain\Actions\Promocode;

use App\Models\Finance\Promocode;
use Illuminate\Http\JsonResponse;

class DeleteSaleInfluencerPromocodeAction
{
    public function execute(int $promocodeId): JsonResponse
    {
        $promocode = Promocode::findOrFail($promocodeId);
        $promocode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Promocode deleted successfully.',
        ]);
    }
}
