<?php

declare(strict_types=1);

namespace App\Domain\Actions\IEX;

use App\Domain\DTOs\IEX\MarketstackIndexStoreDTO;
use App\Models\System\MarketstackIndx;

class CreateMarketstackIndexAction
{
    public function execute(MarketstackIndexStoreDTO $dto): MarketstackIndx
    {
        return MarketstackIndx::create([
            'name' => $dto->name,
            'symbol' => $dto->symbol,
            'country' => $dto->country,
            'currency' => $dto->currency,
            'has_intraday' => $dto->hasIntraday,
            'has_eod' => $dto->hasEod,
            'is_active' => $dto->isActive,
        ]);
    }
}
