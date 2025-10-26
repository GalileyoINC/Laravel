<?php

declare(strict_types=1);

namespace App\Domain\Actions\Settings;

use App\Domain\DTOs\Settings\SettingsUpdateRequestDTO;
use App\Domain\Services\Settings\SettingsServiceInterface;
use Illuminate\Support\Facades\DB;

class UpdateSettingsAction
{
    public function __construct(
        private readonly SettingsServiceInterface $settingsService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function execute(array $data): array
    {
        DB::beginTransaction();

        $dto = new SettingsUpdateRequestDTO(
            settings: $data['settings'] ?? [],
            sms_settings: $data['sms_settings'] ?? null,
            main_settings: $data['main_settings'] ?? null,
            api_settings: $data['api_settings'] ?? null,
            app_settings: $data['app_settings'] ?? null
        );

        $result = $this->settingsService->updateSettings($dto);
        DB::commit();

        return $result;
    }
}
