<?php

declare(strict_types=1);

namespace App\Domain\Actions\Communication;

use App\Domain\DTOs\Communication\SendSmsRequestDTO;
use App\Models\Device\PhoneNumber;
use Illuminate\Support\Facades\Log;

final class SendSmsAction
{
    /**
     * Send an SMS message to the given phone number.
     * This implementation currently simulates send and logs the intent.
     *
     * @return array<string, mixed>
     */
    public function execute(SendSmsRequestDTO $dto): array
    {
        $phone = PhoneNumber::findOrFail($dto->phoneNumberId);

        // Simulate SMS sending (replace with real provider integration when available)
        Log::info('Simulated SMS send', [
            'phone_number_id' => $phone->id,
            'number' => $phone->number,
            'message' => $dto->message,
        ]);

        return [
            'success' => true,
            'phone_number_id' => $phone->id,
        ];
    }
}
