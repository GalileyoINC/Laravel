<?php

declare(strict_types=1);

namespace App\Domain\Actions\Phone;

use App\Domain\DTOs\Phone\PhoneVerifyRequestDTO;
use App\Domain\Services\Phone\PhoneServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VerifyPhoneAction
{
    public function __construct(
        private readonly PhoneServiceInterface $phoneService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
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
    }
}
