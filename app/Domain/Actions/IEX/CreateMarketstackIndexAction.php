<?php

declare(strict_types=1);

namespace App\Domain\Actions\IEX;

use App\Domain\DTOs\IEX\MarketstackIndexStoreDTO;
use App\Models\System\Setting;

class CreateMarketstackIndexAction
{
    public function execute(MarketstackIndexStoreDTO $dto): Setting
    {
        return Setting::create([
            'name' => $dto->name,
            'value' => $dto->symbol,
        ]);
    }
}
