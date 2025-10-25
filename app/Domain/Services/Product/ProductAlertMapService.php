<?php

declare(strict_types=1);

namespace App\Domain\Services\Product;

use App\Domain\DTOs\Product\ProductAlertMapRequestDTO;
use App\Models\ProductDigitalAlerts;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ProductAlertMapService implements ProductAlertMapServiceInterface
{
    /**
     * Get alerts with map data based on filters
     *
     * @return Collection<int, \App\Models\ProductDigitalAlerts>
     */
    public function getAlertsWithMapData(ProductAlertMapRequestDTO $dto): Collection
    {
        try {
            $query = ProductDigitalAlerts::query()
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            // Apply severity filter
            if ($dto->severity) {
                $query->where('severity', $dto->severity);
            }

            // Apply category filter
            if ($dto->category) {
                $query->where('category', $dto->category);
            }

            // Apply geographic bounds filter
            if ($dto->bounds) {
                $query->whereBetween('latitude', [$dto->bounds['south'], $dto->bounds['north']])
                      ->whereBetween('longitude', [$dto->bounds['west'], $dto->bounds['east']]);
            }

            // Apply additional filters
            if (!empty($dto->filter)) {
                if (isset($dto->filter['type'])) {
                    $query->where('type', $dto->filter['type']);
                }
                if (isset($dto->filter['status'])) {
                    $query->where('status', $dto->filter['status']);
                }
                if (isset($dto->filter['active_only']) && $dto->filter['active_only']) {
                    $query->where('status', 'active');
                }
            }

            return $query->orderBy('created_at', 'desc')
                         ->limit($dto->limit ?? 20)
                         ->offset($dto->offset ?? 0)
                         ->get();

        } catch (Exception $e) {
            Log::error('ProductAlertMapService getAlertsWithMapData error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get alerts within geographic bounds
     *
     * @param array<string, float> $bounds
     * @return Collection<int, \App\Models\ProductDigitalAlerts>
     */
    public function getAlertsInBounds(array $bounds, ?int $limit = 20): Collection
    {
        try {
            return ProductDigitalAlerts::query()
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->whereBetween('latitude', [$bounds['south'], $bounds['north']])
                ->whereBetween('longitude', [$bounds['west'], $bounds['east']])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

        } catch (Exception $e) {
            Log::error('ProductAlertMapService getAlertsInBounds error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get alerts by severity level
     *
     * @return Collection<int, \App\Models\ProductDigitalAlerts>
     */
    public function getAlertsBySeverity(string $severity, ?int $limit = 20): Collection
    {
        try {
            return ProductDigitalAlerts::query()
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where('severity', $severity)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

        } catch (Exception $e) {
            Log::error('ProductAlertMapService getAlertsBySeverity error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get alerts by category
     *
     * @return Collection<int, \App\Models\ProductDigitalAlerts>
     */
    public function getAlertsByCategory(string $category, ?int $limit = 20): Collection
    {
        try {
            return ProductDigitalAlerts::query()
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where('category', $category)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

        } catch (Exception $e) {
            Log::error('ProductAlertMapService getAlertsByCategory error: ' . $e->getMessage());
            throw $e;
        }
    }
}
