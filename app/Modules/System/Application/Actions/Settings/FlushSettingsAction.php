<?php

declare(strict_types=1);

namespace App\Modules\System\Application\Actions\Settings;

use App\Services\Settings\SettingsServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FlushSettingsAction
{
    public function __construct(
        private readonly SettingsServiceInterface $settingsService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $this->settingsService->flushSettings();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Settings flushed successfully',
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to flush settings: '.$e->getMessage(),
            ], 500);
        }
    }
}
