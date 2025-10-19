<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Authentication;

/**
 * Authentication response DTO
 */
class AuthResponseDTO
{
    /**
     * @param  array<string, mixed>  $user_profile
     */
    public function __construct(
        public readonly int $user_id,
        public readonly string $access_token,
        public readonly string $refresh_token,
        public readonly int $expires_in,
        public readonly array $user_profile = []
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'] ?? 0,
            access_token: $data['access_token'] ?? '',
            refresh_token: $data['refresh_token'] ?? '',
            expires_in: $data['expires_in'] ?? 3600,
            user_profile: $data['user_profile'] ?? []
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'status' => 'success',
            'user_id' => $this->user_id,
            'access_token' => $this->access_token,
            'refresh_token' => $this->refresh_token,
            'expires_in' => $this->expires_in,
            'user_profile' => $this->user_profile,
        ];
    }
}
