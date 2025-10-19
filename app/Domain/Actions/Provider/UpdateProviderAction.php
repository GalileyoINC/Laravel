<?php

declare(strict_types=1);

namespace App\Domain\Actions\Provider;

use App\Domain\DTOs\Provider\ProviderUpdateDTO;
use App\Models\Finance\Provider;

final class UpdateProviderAction
{
    public function execute(ProviderUpdateDTO $dto): Provider
    {
        $provider = Provider::findOrFail($dto->id);
        $provider->update([
            'name' => $dto->name,
            'email' => $dto->email,
            'country' => $dto->country,
            'is_satellite' => $dto->isSatellite,
        ]);

        return $provider;
    }
}
