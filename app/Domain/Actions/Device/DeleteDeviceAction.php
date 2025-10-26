<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Domain\Services\Device\DeviceServiceInterface;
use Illuminate\Support\Facades\DB;

class DeleteDeviceAction
{
    public function __construct(
        private readonly DeviceServiceInterface $deviceService
    ) {}

    public function execute(int $id): bool
    {
        DB::beginTransaction();

        $this->deviceService->delete($id);
        DB::commit();

        return true;
    }
}
