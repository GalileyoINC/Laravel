<?php

declare(strict_types=1);

namespace App\Domain\Services\Product;

use App\Domain\DTOs\Product\ProductAlertMapRequestDTO;
use Illuminate\Database\Eloquent\Collection;

interface ProductAlertMapServiceInterface
{
    /**
     * Get alerts with map data based on filters
     *
     * @return Collection<int, \App\Models\ProductDigitalAlerts>
     */
    public function getAlertsWithMapData(ProductAlertMapRequestDTO $dto): Collection;

    /**
     * Get alerts within geographic bounds
     *
     * @param array<string, float> $bounds
     * @return Collection<int, \App\Models\ProductDigitalAlerts>
     */
    public function getAlertsInBounds(array $bounds, ?int $limit = 20): Collection;

    /**
     * Get alerts by severity level
     *
     * @return Collection<int, \App\Models\ProductDigitalAlerts>
     */
    public function getAlertsBySeverity(string $severity, ?int $limit = 20): Collection;

    /**
     * Get alerts by category
     *
     * @return Collection<int, \App\Models\ProductDigitalAlerts>
     */
    public function getAlertsByCategory(string $category, ?int $limit = 20): Collection;
}
