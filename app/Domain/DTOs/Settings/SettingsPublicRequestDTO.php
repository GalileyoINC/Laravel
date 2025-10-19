<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Settings;

readonly class SettingsPublicRequestDTO
{
    /**
     * @param  array<string, mixed>|null  $user_point_settings
     * @param  array<string, mixed>|null  $safe_settings
     */
    public function __construct(
        public ?array $user_point_settings,
        public ?array $safe_settings
    ) {}
}
