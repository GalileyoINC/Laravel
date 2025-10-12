<?php

declare(strict_types=1);

namespace App\Services\Product;

use App\DTOs\Product\ApplePurchaseRequestDTO;
use App\DTOs\Product\ProductAlertsRequestDTO;
use App\DTOs\Product\ProductListRequestDTO;
use App\Models\User\User\User;

/**
 * Product service interface
 */
interface ProductServiceInterface
{
    /**
     * Get product list
     */
    public function getProductList(ProductListRequestDTO $dto, ?User $user): array;

    /**
     * Get product alerts
     */
    public function getProductAlerts(ProductAlertsRequestDTO $dto, ?User $user): array;

    /**
     * Process Apple purchase
     */
    public function processApplePurchase(ApplePurchaseRequestDTO $dto, User $user): array;
}
