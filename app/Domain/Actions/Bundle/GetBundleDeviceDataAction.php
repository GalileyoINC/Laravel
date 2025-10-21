<?php

declare(strict_types=1);

namespace App\Domain\Actions\Bundle;

use App\Domain\DTOs\Bundle\BundleDeviceDataRequestDTO;
use App\Domain\Services\Bundle\BundleServiceInterface;
use Exception;

class GetBundleDeviceDataAction
{
    public function __construct(
        private readonly BundleServiceInterface $bundleService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): mixed
    {
        try {
            $dto = new BundleDeviceDataRequestDTO(
                idDevice: $data['idDevice']
            );

            $result = $this->bundleService->getDeviceData($dto);

            return $result;

        } catch (Exception $e) {
            throw $e;
        }
    }
}
