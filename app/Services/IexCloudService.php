<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

/**
 * IEX Cloud Service
 * Handles communication with IEX Cloud API
 */
class IexCloudService
{
    private string $apiUrl;

    private string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.iex.url', 'https://cloud.iexapis.com/stable');
        $this->apiKey = config('services.iex.key', '');
    }

    /**
     * Get data from IEX Cloud API
     */
    /**
     * @return array<string, mixed>
     */
    public function get(string $uri): array
    {
        try {
            $url = $this->apiUrl.'/'.ltrim($uri, '/');
            // Add API key to URL for actual implementation
            $urlWithKey = $url.'?token='.$this->apiKey;

            // Mock implementation - replace with actual API call
            return [
                'success' => true,
                'uri' => $uri,
                'url' => $urlWithKey,
                'data' => [
                    'symbol' => 'AAPL',
                    'price' => 150.00,
                    'change' => 1.50,
                    'changePercent' => 1.01,
                ],
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
     * Get stock quote
     */
    /**
     * @return array<string, mixed>
     */
    public function getQuote(string $symbol): array
    {
        return $this->get("stock/{$symbol}/quote");
    }

    /**
     * Get stock OHLC data
     */
    /**
     * @return array<string, mixed>
     */
    public function getOhlc(string $symbol): array
    {
        return $this->get("stock/{$symbol}/ohlc");
    }
}
