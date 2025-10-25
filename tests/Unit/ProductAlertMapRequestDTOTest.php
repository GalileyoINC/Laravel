<?php

namespace Tests\Unit;

use App\Domain\DTOs\Product\ProductAlertMapRequestDTO;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProductAlertMapRequestDTOTest extends TestCase
{
    public function test_from_array_creates_dto_with_correct_values(): void
    {
        $data = [
            'limit' => 20,
            'offset' => 10,
            'severity' => 'critical',
            'category' => 'weather',
            'bounds' => ['north' => 40.8, 'south' => 40.7],
            'filter' => ['active_only' => true]
        ];

        $dto = ProductAlertMapRequestDTO::fromArray($data);

        $this->assertEquals(20, $dto->limit);
        $this->assertEquals(10, $dto->offset);
        $this->assertEquals('critical', $dto->severity);
        $this->assertEquals('weather', $dto->category);
        $this->assertEquals(['north' => 40.8, 'south' => 40.7], $dto->bounds);
        $this->assertEquals(['active_only' => true], $dto->filter);
    }

    public function test_from_array_handles_missing_values(): void
    {
        $data = [
            'limit' => 10,
        ];

        $dto = ProductAlertMapRequestDTO::fromArray($data);

        $this->assertEquals(10, $dto->limit);
        $this->assertEquals(0, $dto->offset);
        $this->assertNull($dto->severity);
        $this->assertNull($dto->category);
        $this->assertNull($dto->bounds);
        $this->assertEquals([], $dto->filter);
    }

    public function test_from_request_creates_dto_from_request(): void
    {
        $request = new Request([
            'limit' => 15,
            'offset' => 5,
            'severity' => 'high',
            'category' => 'traffic',
            'bounds' => ['north' => 40.9, 'south' => 40.6],
            'filter' => ['active_only' => true, 'expired' => false]
        ]);

        $dto = ProductAlertMapRequestDTO::fromRequest($request);

        $this->assertEquals(15, $dto->limit);
        $this->assertEquals(5, $dto->offset);
        $this->assertEquals('high', $dto->severity);
        $this->assertEquals('traffic', $dto->category);
        $this->assertEquals(['north' => 40.9, 'south' => 40.6], $dto->bounds);
        $this->assertEquals(['active_only' => true, 'expired' => false], $dto->filter);
    }

    public function test_from_request_handles_empty_request(): void
    {
        $request = new Request();

        $dto = ProductAlertMapRequestDTO::fromRequest($request);

        $this->assertEquals(20, $dto->limit); // default value
        $this->assertEquals(0, $dto->offset);
        $this->assertNull($dto->severity);
        $this->assertNull($dto->category);
        $this->assertNull($dto->bounds);
        $this->assertEquals([], $dto->filter);
    }

    public function test_constructor_sets_all_properties(): void
    {
        $dto = new ProductAlertMapRequestDTO(
            limit: 25,
            offset: 15,
            severity: 'medium',
            category: 'security',
            bounds: ['north' => 41.0, 'south' => 40.5],
            filter: ['test' => true]
        );

        $this->assertEquals(25, $dto->limit);
        $this->assertEquals(15, $dto->offset);
        $this->assertEquals('medium', $dto->severity);
        $this->assertEquals('security', $dto->category);
        $this->assertEquals(['north' => 41.0, 'south' => 40.5], $dto->bounds);
        $this->assertEquals(['test' => true], $dto->filter);
    }

    public function test_default_values(): void
    {
        $dto = new ProductAlertMapRequestDTO();

        $this->assertEquals(20, $dto->limit);
        $this->assertEquals(0, $dto->offset);
        $this->assertNull($dto->severity);
        $this->assertNull($dto->category);
        $this->assertNull($dto->bounds);
        $this->assertEquals([], $dto->filter);
    }
}