<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Product;

use Illuminate\Http\Request;

class ProductAlertMapRequestDTO
{
    /**
     * @param array<string, mixed> $filter
     * @param array<string, float>|null $bounds
     */
    public function __construct(
        public readonly ?int $limit = 20,
        public readonly ?int $offset = 0,
        public readonly ?string $severity = null,
        public readonly ?string $category = null,
        public readonly ?array $bounds = null, // north, south, east, west
        public readonly ?array $filter = []
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            limit: isset($data['limit']) ? (int) $data['limit'] : 20,
            offset: isset($data['offset']) ? (int) $data['offset'] : 0,
            severity: $data['severity'] ?? null,
            category: $data['category'] ?? null,
            bounds: $data['bounds'] ?? null,
            filter: $data['filter'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            limit: $request->input('limit', 20),
            offset: $request->input('offset', 0),
            severity: $request->input('severity'),
            category: $request->input('category'),
            bounds: $request->input('bounds'),
            filter: $request->input('filter', [])
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
            'severity' => $this->severity,
            'category' => $this->category,
            'bounds' => $this->bounds,
            'filter' => $this->filter,
        ];
    }

    public function validate(): bool
    {
        $validSeverities = ['critical', 'high', 'medium', 'low'];
        
        if ($this->severity && !in_array($this->severity, $validSeverities)) {
            return false;
        }

        if ($this->bounds && !$this->validateBounds()) {
            return false;
        }

        return $this->limit > 0 && $this->offset >= 0;
    }

    private function validateBounds(): bool
    {
        if (!is_array($this->bounds)) {
            return false;
        }

        $requiredKeys = ['north', 'south', 'east', 'west'];
        foreach ($requiredKeys as $key) {
            if (!isset($this->bounds[$key]) || !is_numeric($this->bounds[$key])) {
                return false;
            }
        }

        return $this->bounds['north'] > $this->bounds['south'] && 
               $this->bounds['east'] > $this->bounds['west'];
    }
}
