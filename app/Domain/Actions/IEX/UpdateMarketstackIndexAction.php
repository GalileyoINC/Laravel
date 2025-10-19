<?php

declare(strict_types=1);

namespace App\Domain\Actions\IEX;

use App\Domain\DTOs\IEX\MarketstackIndexUpdateDTO;
use App\Models\System\Setting;

class UpdateMarketstackIndexAction
{
    public function execute(MarketstackIndexUpdateDTO $dto): Setting
    {
        $setting = Setting::findOrFail($dto->id);
        $setting->update([
            'name' => $dto->name,
            'prod' => $dto->symbol,
            'dev' => $dto->country,
        ]);

        return $setting;
    }
}
