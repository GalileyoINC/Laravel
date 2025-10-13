<?php

declare(strict_types=1);

namespace App\Services\Maintenance;

use App\DTOs\Maintenance\SummarizeRequestDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MaintenanceService implements MaintenanceServiceInterface
{
    private const OPENAI_API_URL = 'https://api.openai.com/v1/chat/completions';
    private const OPENAI_MODEL = 'gpt-3.5-turbo';

    /**
     * Summarize text using OpenAI
     *
     * @param SummarizeRequestDTO $dto
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function summarize(SummarizeRequestDTO $dto): array
    {
        try {
            $apiKey = config('services.openai.api_key');
            
            if (!$apiKey) {
                throw new \Exception('OpenAI API key not configured');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post(self::OPENAI_API_URL, [
                'model' => self::OPENAI_MODEL,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Please summarize the following text to {$dto->size} characters or less:\n" . $dto->text
                    ]
                ],
                'max_tokens' => min($dto->size * 2, 4000), // Allow some buffer for tokens
                'temperature' => 0.3,
            ]);

            if (!$response->successful()) {
                Log::error('OpenAI API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Failed to summarize text');
            }

            $data = $response->json();
            
            if (!isset($data['choices'][0]['message']['content'])) {
                throw new \Exception('Invalid response from OpenAI API');
            }

            return [
                'summarized' => $data['choices'][0]['message']['content']
            ];

        } catch (\Exception $e) {
            Log::error('MaintenanceService::summarize failed', [
                'error' => $e->getMessage(),
                'size' => $dto->size,
                'text_length' => strlen($dto->text)
            ]);
            
            throw $e;
        }
    }
}
