<?php

declare(strict_types=1);

namespace App\Domain\Services\Bundle;

use App\Domain\DTOs\Bundle\BundleCreateRequestDTO;
use App\Domain\DTOs\Bundle\BundleDeviceDataRequestDTO;
use App\Domain\DTOs\Bundle\BundleListRequestDTO;
use App\Domain\DTOs\Bundle\BundleUpdateRequestDTO;
use App\Models\Finance\Bundle;

interface BundleServiceInterface
{
    public function getList(BundleListRequestDTO $dto): array;

    public function create(BundleCreateRequestDTO $dto): Bundle;

    public function update(BundleUpdateRequestDTO $dto): Bundle;

    public function getDeviceData(BundleDeviceDataRequestDTO $dto): array;
}
