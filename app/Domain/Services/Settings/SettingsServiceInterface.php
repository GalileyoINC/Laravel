<?php

declare(strict_types=1);

namespace App\Domain\Services\Settings;

use App\Domain\DTOs\Settings\SettingsPublicRequestDTO;
use App\Domain\DTOs\Settings\SettingsUpdateRequestDTO;

interface SettingsServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getAllSettings(): array;

    /**
     * @return array<string, mixed>
     */
    public function updateSettings(SettingsUpdateRequestDTO $dto): array;

    public function flushSettings(): void;

    /**
     * @return array<string, mixed>
     */
    public function getPublicSettings(SettingsPublicRequestDTO $dto): array;

    /**
     * @return array<string, mixed>
     */
    public function generateBitpayConfig(): array;
}
