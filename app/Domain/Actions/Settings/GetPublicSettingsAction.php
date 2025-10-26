<?php

declare(strict_types=1);

namespace App\Domain\Actions\Settings;

use App\Domain\DTOs\Settings\SettingsPublicRequestDTO;
use App\Domain\Services\Settings\SettingsServiceInterface;
use Illuminate\Http\JsonResponse;

class GetPublicSettingsAction
{
    public function __construct(
        private readonly SettingsServiceInterface $settingsService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $dto = new SettingsPublicRequestDTO(
            user_point_settings: $data['user_point_settings'] ?? null,
            safe_settings: $data['safe_settings'] ?? null
        );

        $result = $this->settingsService->getPublicSettings($dto);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
