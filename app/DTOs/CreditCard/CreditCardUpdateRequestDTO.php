<?php

namespace App\DTOs\CreditCard;

use Illuminate\Http\Request;

class CreditCardUpdateRequestDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $phone = null,
        public readonly ?string $zip = null,
        public readonly ?string $cvv = null,
        public readonly ?int $expirationYear = null,
        public readonly ?int $expirationMonth = null,
        public readonly ?bool $isPreferred = null,
        public readonly ?bool $isAgreeToReceive = null
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            id: (int)$data['id'],
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            phone: $data['phone'] ?? null,
            zip: $data['zip'] ?? null,
            cvv: $data['cvv'] ?? null,
            expirationYear: isset($data['expiration_year']) ? (int)$data['expiration_year'] : null,
            expirationMonth: isset($data['expiration_month']) ? (int)$data['expiration_month'] : null,
            isPreferred: isset($data['is_preferred']) ? (bool)$data['is_preferred'] : null,
            isAgreeToReceive: isset($data['is_agree_to_receive']) ? (bool)$data['is_agree_to_receive'] : null
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            id: $request->input('id'),
            firstName: $request->input('first_name'),
            lastName: $request->input('last_name'),
            phone: $request->input('phone'),
            zip: $request->input('zip'),
            cvv: $request->input('cvv'),
            expirationYear: $request->input('expiration_year'),
            expirationMonth: $request->input('expiration_month'),
            isPreferred: $request->boolean('is_preferred'),
            isAgreeToReceive: $request->boolean('is_agree_to_receive')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'zip' => $this->zip,
            'cvv' => $this->cvv,
            'expiration_year' => $this->expirationYear,
            'expiration_month' => $this->expirationMonth,
            'is_preferred' => $this->isPreferred,
            'is_agree_to_receive' => $this->isAgreeToReceive
        ];
    }

    public function validate(): bool
    {
        if ($this->id <= 0) {
            return false;
        }

        if ($this->expirationMonth && ($this->expirationMonth < 1 || $this->expirationMonth > 12)) {
            return false;
        }

        if ($this->expirationYear && $this->expirationYear < date('Y')) {
            return false;
        }

        return true;
    }
}
