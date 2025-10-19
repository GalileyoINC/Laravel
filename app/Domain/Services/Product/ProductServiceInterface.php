<?php

declare(strict_types=1);

namespace App\Domain\Services\Product;

use App\Domain\DTOs\Product\ApplePurchaseRequestDTO;
use App\Domain\DTOs\Product\ProductAlertsRequestDTO;
use App\Domain\DTOs\Product\ProductListRequestDTO;
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
    public function getProductList(ProductListRequestDTO $dto, ?User $user): array;

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
