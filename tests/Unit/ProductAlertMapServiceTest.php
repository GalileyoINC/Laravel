<?php

namespace Tests\Unit;

use App\Domain\DTOs\Product\ProductAlertMapRequestDTO;
use App\Domain\Services\Product\ProductAlertMapService;
use App\Models\ProductDigitalAlerts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAlertMapServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductAlertMapService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProductAlertMapService();
    }

    public function test_get_alerts_with_map_data_returns_correct_structure(): void
    {
        // Create sample alerts
        ProductDigitalAlerts::factory()->create([
            'type' => 'weather',
            'status' => 'active',
            'title' => 'Severe Weather Warning',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'severity' => 'critical',
            'category' => 'weather',
        ]);

        ProductDigitalAlerts::factory()->create([
            'type' => 'traffic',
            'status' => 'active',
            'title' => 'Traffic Accident',
            'latitude' => 40.7589,
            'longitude' => -73.9857,
            'severity' => 'high',
            'category' => 'traffic',
        ]);

        $dto = new ProductAlertMapRequestDTO(
            limit: 10,
            offset: 0,
            severity: null,
            category: null,
            bounds: null,
            filter: ['active_only' => true]
        );

        $result = $this->service->getAlertsWithMapData($dto);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertCount(2, $result);
        
        // Check first alert structure
        $firstAlert = $result->first();
        $this->assertArrayHasKey('id', $firstAlert->toArray());
        $this->assertArrayHasKey('title', $firstAlert->toArray());
        $this->assertArrayHasKey('latitude', $firstAlert->toArray());
        $this->assertArrayHasKey('longitude', $firstAlert->toArray());
        $this->assertArrayHasKey('severity', $firstAlert->toArray());
        $this->assertArrayHasKey('category', $firstAlert->toArray());
    }

    public function test_get_alerts_filters_by_severity(): void
    {
        ProductDigitalAlerts::factory()->create([
            'severity' => 'critical',
            'status' => 'active',
        ]);

        ProductDigitalAlerts::factory()->create([
            'severity' => 'low',
            'status' => 'active',
        ]);

        $dto = new ProductAlertMapRequestDTO(
            limit: 10,
            offset: 0,
            severity: 'critical',
            category: null,
            bounds: null,
            filter: ['active_only' => true]
        );

        $result = $this->service->getAlertsWithMapData($dto);

        $this->assertCount(1, $result);
        $this->assertEquals('critical', $result->first()->severity);
    }

    public function test_get_alerts_filters_by_category(): void
    {
        ProductDigitalAlerts::factory()->create([
            'category' => 'weather',
            'status' => 'active',
        ]);

        ProductDigitalAlerts::factory()->create([
            'category' => 'traffic',
            'status' => 'active',
        ]);

        $dto = new ProductAlertMapRequestDTO(
            limit: 10,
            offset: 0,
            severity: null,
            category: 'weather',
            bounds: null,
            filter: ['active_only' => true]
        );

        $result = $this->service->getAlertsWithMapData($dto);

        $this->assertCount(1, $result);
        $this->assertEquals('weather', $result->first()->category);
    }

    public function test_get_alerts_respects_limit(): void
    {
        ProductDigitalAlerts::factory()->count(5)->create([
            'status' => 'active',
        ]);

        $dto = new ProductAlertMapRequestDTO(
            limit: 3,
            offset: 0,
            severity: null,
            category: null,
            bounds: null,
            filter: ['active_only' => true]
        );

        $result = $this->service->getAlertsWithMapData($dto);

        $this->assertCount(3, $result);
    }

    public function test_get_alerts_respects_offset(): void
    {
        ProductDigitalAlerts::factory()->count(5)->create([
            'status' => 'active',
        ]);

        $dto = new ProductAlertMapRequestDTO(
            limit: 2,
            offset: 2,
            severity: null,
            category: null,
            bounds: null,
            filter: ['active_only' => true]
        );

        $result = $this->service->getAlertsWithMapData($dto);

        $this->assertCount(2, $result);
    }

    public function test_get_alerts_filters_active_only(): void
    {
        ProductDigitalAlerts::factory()->active()->create();

        ProductDigitalAlerts::factory()->inactive()->create();

        $dto = new ProductAlertMapRequestDTO(
            limit: 10,
            offset: 0,
            severity: null,
            category: null,
            bounds: null,
            filter: ['active_only' => true]
        );

        $result = $this->service->getAlertsWithMapData($dto);

        $this->assertCount(1, $result);
        $this->assertNotNull($result->first());
        $this->assertEquals('active', $result->first()->status);
    }
}