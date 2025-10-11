<?php

namespace App\DTOs\Customer;

class UpdatePrivacyRequestDTO
{
    public function __construct(
        public int $generalVisibility,
        public int $phoneVisibility,
        public int $addressVisibility
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            generalVisibility: (int) ($data['generalVisibility'] ?? 0),
            phoneVisibility: (int) ($data['phoneVisibility'] ?? 0),
            addressVisibility: (int) ($data['addressVisibility'] ?? 0)
        );
    }

    public function validate(): bool
    {
        return in_array($this->generalVisibility, [0, 1]) &&
               in_array($this->phoneVisibility, [0, 1]) &&
               in_array($this->addressVisibility, [0, 1]);
    }
}
