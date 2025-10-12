<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\Services\Settings\SettingsServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetSettingsAction
{
    public function __construct(
        private readonly SettingsServiceInterface $settingsService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $result = $this->settingsService->getAllSettings();

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get settings: '.$e->getMessage(),
            ], 500);
        }
    }
}
