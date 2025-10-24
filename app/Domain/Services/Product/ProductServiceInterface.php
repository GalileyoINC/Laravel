<?php

declare(strict_types=1);

namespace App\Domain\Services\Product;

use App\Domain\DTOs\Product\ApplePurchaseRequestDTO;
use App\Domain\DTOs\Product\ProductAlertsRequestDTO;
use App\Models\User\User;

/**
 * Product service interface
 */
interface ProductServiceInterface
{
    /**
     * Get product list
     *
     * @return array<string, mixed>
     */
    public function getProductList(int $page, int $limit, ?string $search, ?string $category, ?int $status, string $sortBy, string $sortOrder, ?User $user): array;

    /**
     * Get product alerts
     *
     * @return array<string, mixed>
     */
    public function getProductAlerts(ProductAlertsRequestDTO $dto, ?User $user): array;

    /**
     * Process Apple purchase
     *
     * @return array<string, mixed>
     */
    public function processApplePurchase(ApplePurchaseRequestDTO $dto, User $user): array;
}
