<?php

declare(strict_types=1);

namespace App\Domain\Actions\Provider;

use App\Domain\DTOs\Provider\ProviderCreateDTO;
use App\Models\Finance\Provider;

final class CreateProviderAction
{
    public function execute(ProviderCreateDTO $dto): Provider
    {
        return Provider::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'country' => $dto->country,
            'is_satellite' => $dto->isSatellite,
        ]);
    }
}
