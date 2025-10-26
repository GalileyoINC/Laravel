<?php

declare(strict_types=1);

namespace App\Domain\Actions\Bundle;

use App\Domain\Services\Bundle\BundleServiceInterface;

class GetBundleListAction
{
    public function __construct(
        private readonly BundleServiceInterface $bundleService
    ) {}

    public function execute(
        int $page = 1,
        int $limit = 20,
        ?string $search = null,
        ?int $status = null
    ): array {
        $bundles = $this->bundleService->getList($page, $limit, $search, $status);

        return $bundles;
    }
}
