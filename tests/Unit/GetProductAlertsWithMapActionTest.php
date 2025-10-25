<?php

namespace Tests\Unit;

use App\Domain\Actions\Product\GetProductAlertsWithMapAction;
use App\Domain\DTOs\Product\ProductAlertMapRequestDTO;
use App\Domain\Services\Product\ProductAlertMapServiceInterface;
use App\Models\ProductDigitalAlerts;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class GetProductAlertsWithMapActionTest extends TestCase
{
    use RefreshDatabase;

    private GetProductAlertsWithMapAction $action;
    private ProductAlertMapServiceInterface $mockService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockService = $this->createMock(ProductAlertMapServiceInterface::class);
        $this->action = new GetProductAlertsWithMapAction($this->mockService);
    }

    public function test_execute_returns_json_response(): void
    {
        $mockAlerts = ProductDigitalAlerts::factory()->count(1)->create();

        $this->mockService
            ->expects($this->once())
            ->method('getAlertsWithMapData')
            ->willReturn($mockAlerts);

        $request = [
            'limit' => 10,
            'offset' => 0,
            'filter' => ['active_only' => true]
        ];

        $response = $this->action->execute($request);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        
        $responseData = $response->getData(true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('meta', $responseData);
    }

    public function test_execute_handles_empty_results(): void
    {
        $this->mockService
            ->expects($this->once())
            ->method('getAlertsWithMapData')
            ->willReturn(new Collection([]));

        $request = [
            'limit' => 10,
            'offset' => 0,
            'filter' => ['active_only' => true]
        ];

        $response = $this->action->execute($request);

        $responseData = $response->getData(true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEmpty($responseData['data']);
        $this->assertEquals(0, $responseData['meta']['total']);
    }

    public function test_execute_passes_correct_parameters_to_service(): void
    {
        $expectedDto = new ProductAlertMapRequestDTO(
            limit: 5,
            offset: 10,
            severity: 'critical',
            category: 'weather',
            bounds: ['north' => 40.8, 'south' => 40.7, 'east' => -73.9, 'west' => -74.1],
            filter: ['active_only' => true]
        );

        $this->mockService
            ->expects($this->once())
            ->method('getAlertsWithMapData')
            ->with($this->callback(function ($dto) use ($expectedDto) {
                return $dto instanceof ProductAlertMapRequestDTO &&
                       $dto->limit === $expectedDto->limit &&
                       $dto->offset === $expectedDto->offset &&
                       $dto->severity === $expectedDto->severity &&
                       $dto->category === $expectedDto->category;
            }))
            ->willReturn(new Collection([]));

        $request = [
            'limit' => 5,
            'offset' => 10,
            'severity' => 'critical',
            'category' => 'weather',
            'bounds' => ['north' => 40.8, 'south' => 40.7, 'east' => -73.9, 'west' => -74.1],
            'filter' => ['active_only' => true]
        ];

        $this->action->execute($request);
    }

    public function test_execute_sets_correct_meta_data(): void
    {
        $mockAlerts = ProductDigitalAlerts::factory()->count(2)->create();

        $this->mockService
            ->expects($this->once())
            ->method('getAlertsWithMapData')
            ->willReturn($mockAlerts);

        $request = [
            'limit' => 10,
            'offset' => 0,
            'filter' => ['active_only' => true]
        ];

        $response = $this->action->execute($request);
        $responseData = $response->getData(true);

        $this->assertEquals(2, $responseData['meta']['total']);
        $this->assertEquals(10, $responseData['meta']['limit']);
        $this->assertEquals(0, $responseData['meta']['offset']);
    }
}