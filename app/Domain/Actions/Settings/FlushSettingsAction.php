<?php

declare(strict_types=1);

namespace App\Domain\Actions\Settings;

use App\Domain\Services\Settings\SettingsServiceInterface;
use Illuminate\Support\Facades\DB;

class FlushSettingsAction
{
    public function __construct(
        private readonly SettingsServiceInterface $settingsService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): bool
    {
        DB::beginTransaction();

        $this->settingsService->flushSettings();
        DB::commit();

        return true;
    }
}
