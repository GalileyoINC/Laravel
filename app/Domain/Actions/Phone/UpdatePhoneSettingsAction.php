<?php

declare(strict_types=1);

namespace App\Domain\Actions\Phone;

use App\Domain\DTOs\Phone\PhoneUpdateRequestDTO;
use App\Domain\Services\Phone\PhoneServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdatePhoneSettingsAction
{
    public function __construct(
        private readonly PhoneServiceInterface $phoneService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $dto = PhoneUpdateRequestDTO::fromArray($data);
        if (! $dto->validate()) {
            return response()->json([
                'errors' => ['Invalid phone update request'],
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

        $phone = $this->phoneService->updatePhoneSettings($dto, $user);

        return response()->json($phone->toArray());
    }
}
