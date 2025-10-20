<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Authentication;

use Illuminate\Http\Request;

/**
 * Login request DTO
 */
class LoginRequestDTO
{
    /**
     * @param  array<string, mixed>  $device
     */
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly array $device
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
            device: $data['device'] ?? []
        );
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->input('email', ''),
            password: $request->input('password', ''),
            device: $request->input('device', [])
        );
    }

    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'device' => $this->device,
        ];
    }

    public function validate(): bool
    {
        if (empty($this->email) || ! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (empty($this->password) || mb_strlen($this->password) < 3) {
            return false;
        }

        return true;
    }
}
