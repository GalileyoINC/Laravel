<?php

declare(strict_types=1);

namespace App\Domain\Actions\Settings;

use App\Domain\Services\Settings\SettingsServiceInterface;
use Illuminate\Http\JsonResponse;

class GetSettingsAction
{
    public function __construct(
        private readonly SettingsServiceInterface $settingsService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $result = $this->settingsService->getAllSettings();

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
