<?php

namespace App\Actions\Phone;

use App\DTOs\Phone\PhoneUpdateRequestDTO;
use App\Services\Phone\PhoneServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdatePhoneSettingsAction
{
    public function __construct(
        private PhoneServiceInterface $phoneService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = PhoneUpdateRequestDTO::fromArray($data);
            if (!$dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid phone update request'],
                    'message' => 'Invalid request parameters'
                ], 400);
            }

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $phone = $this->phoneService->updatePhoneSettings($dto, $user);

            return response()->json($phone->toArray());

        } catch (\Exception $e) {
            Log::error('UpdatePhoneSettingsAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
