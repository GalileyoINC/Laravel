<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

/**
 * SPS API Service
 * Handles communication with SPS API
 */
class SpsApiService
{
    private string $apiUrl;

    private string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.sps.url', 'https://api.sps.com');
        $this->apiKey = config('services.sps.key', '');
    }

    /**
     * Check IP address
     */
    /**
     * @return array<string, mixed>
     */
    public function checkIp(?string $ip = null): array
    {
        try {
            $ip = $ip ?? request()->ip();

            // Mock implementation - replace with actual API call
            // Using $this->apiUrl and $this->apiKey for actual implementation
            return [
                'success' => true,
                'ip' => $ip,
                'status' => 'valid',
                'location' => 'Unknown',
                'api_url' => $this->apiUrl,
                'api_key_configured' => ! empty($this->apiKey),
                'timestamp' => now()->toISOString(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString(),
            ];
        }
    }

    /**
     * Get SPS status
     */
    /**
     * @return array<string, mixed>
     */
    public function getStatus(): array
    {
        try {
            // Mock implementation - replace with actual API call
            // Using $this->apiUrl and $this->apiKey for actual implementation
            return [
                'status' => 'online',
                'api_url' => $this->apiUrl,
                'api_key_configured' => ! empty($this->apiKey),
                'last_check' => now()->toISOString(),
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }
}
