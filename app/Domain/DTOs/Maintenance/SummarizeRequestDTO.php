<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Maintenance;

use Illuminate\Http\Request;
use InvalidArgumentException;

class SummarizeRequestDTO
{
    public function __construct(
        public readonly int $size,
        public readonly string $text
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            size: (int) $data['size'],
            text: (string) $data['text']
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            size: (int) $request->input('size'),
            text: (string) $request->input('text')
        );
    }

    /**
     * Convert to array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'size' => $this->size,
            'text' => $this->text,
        ];
    }

    /**
     * Validate the DTO data
     *
     * @throws InvalidArgumentException
     */
    public function validate(): void
    {
        if ($this->size < 1 || $this->size > 10000) {
            throw new InvalidArgumentException('Size must be between 1 and 10000 characters');
        }

        if (empty($this->text) || mb_strlen($this->text) > 50000) {
            throw new InvalidArgumentException('Text must be between 1 and 50000 characters');
        }
    }
}
