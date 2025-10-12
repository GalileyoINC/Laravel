<?php

declare(strict_types=1);

namespace App\Services\Settings;

use App\DTOs\Settings\SettingsPublicRequestDTO;
use App\DTOs\Settings\SettingsUpdateRequestDTO;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

interface SettingsServiceInterface
{
    public function getAllSettings(): array;

    public function updateSettings(SettingsUpdateRequestDTO $dto): array;

    public function flushSettings(): void;

    public function getPublicSettings(SettingsPublicRequestDTO $dto): array;

    public function generateBitpayConfig(): array;
}

// SettingsService.php
class SettingsService implements SettingsServiceInterface
{
    public function getAllSettings(): array
    {
        return Cache::remember('all_settings', 3600, fn () => [
            'sms' => config('services.sms'),
            'main' => config('app'),
            'api' => config('services.api'),
            'app' => config('services.app'),
        ]);
    }

    public function updateSettings(SettingsUpdateRequestDTO $dto): array
    {
        if ($dto->sms_settings) {
            foreach ($dto->sms_settings as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => 'sms.'.$key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }
        }

        if ($dto->main_settings) {
            foreach ($dto->main_settings as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => 'main.'.$key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }
        }

        if ($dto->api_settings) {
            foreach ($dto->api_settings as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => 'api.'.$key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }
        }

        if ($dto->app_settings) {
            foreach ($dto->app_settings as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => 'app.'.$key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }
        }

        $this->flushSettings();

        return $this->getAllSettings();
    }

    public function flushSettings(): void
    {
        Cache::forget('all_settings');
        Cache::forget('public_settings');
    }

    public function getPublicSettings(SettingsPublicRequestDTO $dto): array
    {
        return Cache::remember('public_settings', 3600, fn () => [
            'user_point' => DB::table('settings')->where('key', 'like', 'user_point.%')->pluck('value', 'key'),
            'safe' => DB::table('settings')->where('key', 'like', 'safe.%')->pluck('value', 'key'),
        ]);
    }

    public function generateBitpayConfig(): array
    {
        $pairingCode = bin2hex(random_bytes(16));

        DB::table('settings')->updateOrInsert(
            ['key' => 'bitpay.pairing_code'],
            ['value' => $pairingCode, 'updated_at' => now()]
        );

        return [
            'pairing_code' => $pairingCode,
            'generated_at' => now(),
        ];
    }
}
