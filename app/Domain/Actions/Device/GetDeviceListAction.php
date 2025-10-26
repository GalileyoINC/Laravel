<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Domain\Services\Device\DeviceServiceInterface;

class GetDeviceListAction
{
    public function __construct(
        private readonly DeviceServiceInterface $deviceService
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function execute(
        int $page = 1,
        int $limit = 20,
        ?string $search = null,
        ?int $userId = null,
        ?string $os = null,
        ?string $pushTokenFill = null,
        ?string $pushToken = null,
        ?string $pushTurnOn = null,
        ?string $updatedAtFrom = null,
        ?string $updatedAtTo = null
    ): array {
        return $this->deviceService->getList($page, $limit, $search, $userId, $os, $pushTokenFill, $pushToken, $pushTurnOn, $updatedAtFrom, $updatedAtTo);
    }
}
