<?php

declare(strict_types=1);

namespace App\Services\Settings;

use App\DTOs\Settings\SettingsPublicRequestDTO;
use App\DTOs\Settings\SettingsUpdateRequestDTO;

interface SettingsServiceInterface
{
    public function getAllSettings(): array;

    public function updateSettings(SettingsUpdateRequestDTO $dto): array;

    public function flushSettings(): void;

    public function getPublicSettings(SettingsPublicRequestDTO $dto): array;

    public function generateBitpayConfig(): array;
}
