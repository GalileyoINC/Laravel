<?php

declare(strict_types=1);

namespace App\Modules\Communication\Application\Actions\Phone;

use App\DTOs\Phone\PhoneVerifyRequestDTO;
use App\Services\Phone\PhoneServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VerifyPhoneAction
{
    public function __construct(
        private readonly PhoneServiceInterface $phoneService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = PhoneVerifyRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid phone verification request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $response = $this->phoneService->verifyPhone($dto, $user);

            return response()->json($response->toArray());

        } catch (Exception $e) {
            Log::error('VerifyPhoneAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
