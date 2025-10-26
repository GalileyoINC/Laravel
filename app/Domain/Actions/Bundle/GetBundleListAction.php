<?php

declare(strict_types=1);

namespace App\Domain\Actions\Bundle;

use App\Domain\Services\Bundle\BundleServiceInterface;
use App\Models\Finance\Bundle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
    ): LengthAwarePaginator {
        $query = Bundle::query();

        if ($search) {
            $query->where('title', 'like', '%'.$search.'%');
        }

        if ($status !== null) {
            $query->where('is_active', $status);
        }

        return $query->orderBy('id', 'desc')->paginate($limit, ['*'], 'page', $page);
    }
}
