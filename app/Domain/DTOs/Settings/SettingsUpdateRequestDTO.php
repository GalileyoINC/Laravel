<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Settings;

readonly class SettingsUpdateRequestDTO
{
    public function __construct(
        public array $settings,
        public ?array $sms_settings = null,
        public ?array $main_settings = null,
        public ?array $api_settings = null,
        public ?array $app_settings = null
    ) {}

    public function toArray(): array
    {
        return [
            'settings' => $this->settings,
            'sms_settings' => $this->sms_settings,
            'main_settings' => $this->main_settings,
            'api_settings' => $this->api_settings,
            'app_settings' => $this->app_settings,
        ];
    }
}
