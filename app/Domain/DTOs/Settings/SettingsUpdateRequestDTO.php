<?php

declare(strict_types=1);

namespace App\DTOs\Settings;

readonly class SettingsUpdateRequestDTO
{
    public function __construct(
        public ?array $sms_settings,
        public ?array $main_settings,
        public ?array $api_settings,
        public ?array $app_settings
    ) {}
}

// SettingsPublicRequestDTO

namespace App\Domain\DTOs\Settings;

readonly class SettingsPublicRequestDTO
{
    public function __construct(
        public ?array $user_point_settings,
        public ?array $safe_settings
    ) {}
}
