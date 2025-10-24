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
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function execute(array $data): array
    {
        $page = $data['page'] ?? 1;
        $limit = $data['limit'] ?? 20;
        $search = $data['search'] ?? null;
        $userId = $data['user_id'] ?? null;
        $os = $data['os'] ?? null;
        $pushTokenFill = $data['push_token_fill'] ?? null;
        $pushToken = $data['push_token'] ?? null;
        $pushTurnOn = $data['push_turn_on'] ?? null;
        $updatedAtFrom = $data['updated_at_from'] ?? null;
        $updatedAtTo = $data['updated_at_to'] ?? null;

        return $this->deviceService->getList($page, $limit, $search, $userId, $os, $pushTokenFill, $pushToken, $pushTurnOn, $updatedAtFrom, $updatedAtTo);
    }
}
