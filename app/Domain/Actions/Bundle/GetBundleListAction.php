<?php

declare(strict_types=1);

namespace App\Domain\Actions\Bundle;

use App\Domain\Services\Bundle\BundleServiceInterface;
use Exception;

class GetBundleListAction
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
            $page = $data['page'] ?? 1;
            $limit = $data['limit'] ?? 20;
            $search = $data['search'] ?? null;
            $status = $data['status'] ?? null;

            $bundles = $this->bundleService->getList($page, $limit, $search, $status);

            return $bundles;

        } catch (Exception $e) {
            throw $e;
        }
    }
}
