<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

/**
 * SMS Service
 * Handles SMS sending through various providers
 */
class SmsService
{
    private string $provider;

    /**
     * @var array<string, mixed>
     */
    private array $response = [];

    public function __construct(string $provider = 'twilio')
    {
        $this->provider = $provider;
    }

    /**
     * Send SMS message
     */
    public function send(string $number, string $body): bool
    {
        try {
            switch ($this->provider) {
                case 'twilio':
                    return $this->sendViaTwilio($number, $body);
                case 'bivystick':
                    return $this->sendViaBivyStick($number, $body);
                default:
                    throw new Exception("Unsupported SMS provider: {$this->provider}");
            }
        } catch (Exception $e) {
            $this->response = [
                'success' => false,
                'error' => $e->getMessage(),
            ];

            return false;
        }
    }

    /**
     * Get the last response
     */
    /**
     * @return array<string, mixed>
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * Get provider
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * Send via Twilio
     */
    private function sendViaTwilio(string $number, string $body): bool
    {
        // Mock implementation - replace with actual Twilio API call
        $this->response = [
            'success' => true,
            'provider' => 'twilio',
            'sid' => 'SM'.uniqid(),
            'status' => 'sent',
            'to' => $number,
            'body' => $body,
        ];

        return true;
    }

    /**
     * Send via BivyStick
     */
    private function sendViaBivyStick(string $number, string $body): bool
    {
        // Mock implementation - replace with actual BivyStick API call
        $this->response = [
            'success' => true,
            'provider' => 'bivystick',
            'message_id' => uniqid(),
            'status' => 'sent',
            'to' => $number,
            'body' => $body,
        ];

        return true;
    }
}
