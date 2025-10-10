<?php

namespace App\DTOs\Customer;

use Illuminate\Http\Request;

class UpdateProfileRequestDTO
{
    public function __construct(
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?string $country = null,
        public readonly ?string $state = null,
        public readonly ?string $city = null,
        public readonly ?string $zipCode = null,
        public readonly ?string $address = null,
        public readonly ?string $bio = null,
        public readonly ?string $website = null,
        public readonly ?string $timezone = null
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            country: $data['country'] ?? null,
            state: $data['state'] ?? null,
            city: $data['city'] ?? null,
            zipCode: $data['zip_code'] ?? null,
            address: $data['address'] ?? null,
            bio: $data['bio'] ?? null,
            website: $data['website'] ?? null,
            timezone: $data['timezone'] ?? null
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            firstName: $request->input('first_name'),
            lastName: $request->input('last_name'),
            email: $request->input('email'),
            phone: $request->input('phone'),
            country: $request->input('country'),
            state: $request->input('state'),
            city: $request->input('city'),
            zipCode: $request->input('zip_code'),
            address: $request->input('address'),
            bio: $request->input('bio'),
            website: $request->input('website'),
            timezone: $request->input('timezone')
        );
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'zip_code' => $this->zipCode,
            'address' => $this->address,
            'bio' => $this->bio,
            'website' => $this->website,
            'timezone' => $this->timezone
        ];
    }

    public function validate(): bool
    {
        // Basic validation - at least one field should be provided
        return !empty(array_filter($this->toArray()));
    }
}
