<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\DTOs\Settings\SettingsUpdateRequestDTO;
use App\Services\Settings\SettingsServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UpdateSettingsAction
{
    public function __construct(
        private readonly SettingsServiceInterface $settingsService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $dto = new SettingsUpdateRequestDTO(
                sms_settings: $data['sms_settings'] ?? null,
                main_settings: $data['main_settings'] ?? null,
                api_settings: $data['api_settings'] ?? null,
                app_settings: $data['app_settings'] ?? null
            );

            $result = $this->settingsService->updateSettings($dto);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Settings updated successfully',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update settings: '.$e->getMessage(),
            ], 500);
        }
    }
}
