<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Payment;

/**
 * PaymentDetailsDTO
 * DTO for credit card payment details
 */
class PaymentDetailsDTO
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $phone,
        public readonly string $card_number,
        public readonly string $security_code,
        public readonly string $expiration_year,
        public readonly string $expiration_month,
        public readonly string $zip,
        public readonly bool $is_agree_to_receive = true,
        public readonly ?int $id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            first_name: $data['first_name'],
            last_name: $data['last_name'],
            phone: $data['phone'],
            card_number: $data['card_number'],
            security_code: $data['security_code'],
            expiration_year: $data['expiration_year'],
            expiration_month: $data['expiration_month'],
            zip: $data['zip'],
            is_agree_to_receive: $data['is_agree_to_receive'] ?? true,
            id: $data['id'] ?? null,
        );
    }

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'card_number' => $this->card_number,
            'security_code' => $this->security_code,
            'expiration_year' => $this->expiration_year,
            'expiration_month' => $this->expiration_month,
            'zip' => $this->zip,
            'is_agree_to_receive' => $this->is_agree_to_receive,
            'id' => $this->id,
        ];
    }
}
