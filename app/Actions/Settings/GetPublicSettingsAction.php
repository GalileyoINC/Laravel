<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\DTOs\Settings\SettingsPublicRequestDTO;
use App\Services\Settings\SettingsServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetPublicSettingsAction
{
    public function __construct(
        private readonly SettingsServiceInterface $settingsService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = new SettingsPublicRequestDTO(
                user_point_settings: $data['user_point_settings'] ?? null,
                safe_settings: $data['safe_settings'] ?? null
            );

            $result = $this->settingsService->getPublicSettings($dto);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get public settings: '.$e->getMessage(),
            ], 500);
        }
    }
}
