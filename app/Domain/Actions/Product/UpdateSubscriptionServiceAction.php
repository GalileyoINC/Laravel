<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\SubscriptionUpdateDTO;
use App\Models\Finance\Service;

final class UpdateSubscriptionServiceAction
{
    public function execute(SubscriptionUpdateDTO $dto): Service
    {
        $service = Service::findOrFail($dto->serviceId);
        $service->update([
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'special_price' => $dto->specialPrice,
            'is_special_price' => $dto->isSpecialPrice,
            'is_active' => $dto->isActive,
            'settings' => $dto->settings,
        ]);

        return $service;
    }
}
