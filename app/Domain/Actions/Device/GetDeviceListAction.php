<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Domain\DTOs\Device\DeviceListRequestDTO;
use App\Domain\Services\Device\DeviceServiceInterface;

class GetDeviceListAction
{
    public function __construct(
        private readonly DeviceServiceInterface $deviceService
    ) {}

    public function execute(array $data): array
    {
        $dto = new DeviceListRequestDTO(
            page: $data['page'] ?? 1,
            limit: $data['limit'] ?? 20,
            search: $data['search'] ?? null,
            user_id: $data['user_id'] ?? null,
            os: $data['os'] ?? null,
            pushTokenFill: $data['push_token_fill'] ?? null,
            pushToken: $data['push_token'] ?? null,
            pushTurnOn: $data['push_turn_on'] ?? null,
            updatedAtFrom: $data['updated_at_from'] ?? null,
            updatedAtTo: $data['updated_at_to'] ?? null
        );

        return $this->deviceService->getList($dto);
    }
}
