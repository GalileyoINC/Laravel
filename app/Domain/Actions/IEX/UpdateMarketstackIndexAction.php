<?php

declare(strict_types=1);

namespace App\Domain\Actions\IEX;

use App\Domain\DTOs\IEX\MarketstackIndexUpdateDTO;
use App\Models\System\MarketstackIndx;

class UpdateMarketstackIndexAction
{
    public function execute(MarketstackIndexUpdateDTO $dto): MarketstackIndx
    {
        $index = MarketstackIndx::findOrFail($dto->id);
        $index->update([
            'name' => $dto->name,
            'symbol' => $dto->symbol,
            'country' => $dto->country,
            'currency' => $dto->currency,
            'has_intraday' => $dto->hasIntraday,
            'has_eod' => $dto->hasEod,
            'is_active' => $dto->isActive,
        ]);

        return $index;
    }
}
