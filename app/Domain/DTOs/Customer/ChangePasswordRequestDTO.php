<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Customer;

use Illuminate\Http\Request;

class ChangePasswordRequestDTO
{
    public function __construct(
        public readonly string $currentPassword,
        public readonly string $newPassword,
        public readonly string $confirmPassword
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            currentPassword: $data['current_password'] ?? '',
            newPassword: $data['new_password'] ?? '',
            confirmPassword: $data['confirm_password'] ?? ''
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            currentPassword: $request->input('current_password', ''),
            newPassword: $request->input('new_password', ''),
            confirmPassword: $request->input('confirm_password', '')
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'current_password' => $this->currentPassword,
            'new_password' => $this->newPassword,
            'confirm_password' => $this->confirmPassword,
        ];
    }

    public function validate(): bool
    {
        return ! empty($this->currentPassword) &&
               ! empty($this->newPassword) &&
               ! empty($this->confirmPassword) &&
               $this->newPassword === $this->confirmPassword &&
               mb_strlen($this->newPassword) >= 8;
    }
}
