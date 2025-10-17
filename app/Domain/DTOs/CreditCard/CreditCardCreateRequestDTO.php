<?php

declare(strict_types=1);

namespace App\Domain\DTOs\CreditCard;

use Illuminate\Http\Request;

class CreditCardCreateRequestDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $num,
        public readonly string $phone,
        public readonly string $zip,
        public readonly string $cvv,
        public readonly int $expirationYear,
        public readonly int $expirationMonth,
        public readonly bool $isAgreeToReceive = false
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            firstName: $data['first_name'],
            lastName: $data['last_name'],
            num: $data['num'],
            phone: $data['phone'],
            zip: $data['zip'],
            cvv: $data['cvv'],
            expirationYear: (int) $data['expiration_year'],
            expirationMonth: (int) $data['expiration_month'],
            isAgreeToReceive: (bool) ($data['is_agree_to_receive'] ?? false)
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            firstName: $request->input('first_name'),
            lastName: $request->input('last_name'),
            num: $request->input('num'),
            phone: $request->input('phone'),
            zip: $request->input('zip'),
            cvv: $request->input('cvv'),
            expirationYear: $request->input('expiration_year'),
            expirationMonth: $request->input('expiration_month'),
            isAgreeToReceive: $request->boolean('is_agree_to_receive')
        );
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'num' => $this->num,
            'phone' => $this->phone,
            'zip' => $this->zip,
            'cvv' => $this->cvv,
            'expiration_year' => $this->expirationYear,
            'expiration_month' => $this->expirationMonth,
            'is_agree_to_receive' => $this->isAgreeToReceive,
        ];
    }

    public function validate(): bool
    {
        return ! empty($this->firstName) &&
               ! empty($this->lastName) &&
               ! empty($this->num) &&
               ! empty($this->phone) &&
               ! empty($this->zip) &&
               ! empty($this->cvv) &&
               $this->expirationYear > 0 &&
               $this->expirationMonth > 0 &&
               $this->expirationMonth <= 12;
    }
}
