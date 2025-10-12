<?php

declare(strict_types=1);

namespace App\Services\Bundle;

use App\DTOs\Bundle\BundleCreateRequestDTO;
use App\DTOs\Bundle\BundleDeviceDataRequestDTO;
use App\DTOs\Bundle\BundleListRequestDTO;
use App\DTOs\Bundle\BundleUpdateRequestDTO;
use App\Models\Finance\Bundle;

interface BundleServiceInterface
{
    public function getList(BundleListRequestDTO $dto): array;

    public function create(BundleCreateRequestDTO $dto): Bundle;

    public function update(BundleUpdateRequestDTO $dto): Bundle;

    public function getDeviceData(BundleDeviceDataRequestDTO $dto): array;
}
