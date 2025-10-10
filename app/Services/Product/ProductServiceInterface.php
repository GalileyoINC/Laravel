<?php

namespace App\Services\Product;

use App\DTOs\Product\ProductListRequestDTO;
use App\DTOs\Product\ProductAlertsRequestDTO;
use App\DTOs\Product\ApplePurchaseRequestDTO;
use App\Models\User;

/**
 * Product service interface
 */
interface ProductServiceInterface
{
    /**
     * Get product list
     *
     * @param ProductListRequestDTO $dto
     * @param User|null $user
     * @return array
     */
    public function getProductList(ProductListRequestDTO $dto, ?User $user): array;

    /**
     * Get product alerts
     *
     * @param ProductAlertsRequestDTO $dto
     * @param User|null $user
     * @return array
     */
    public function getProductAlerts(ProductAlertsRequestDTO $dto, ?User $user): array;

    /**
     * Process Apple purchase
     *
     * @param ApplePurchaseRequestDTO $dto
     * @param User $user
     * @return array
     */
    public function processApplePurchase(ApplePurchaseRequestDTO $dto, User $user): array;
}
