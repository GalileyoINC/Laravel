<?php

declare(strict_types=1);

namespace App\Domain\Actions\Customer;

use App\Domain\DTOs\Customer\UpdateProfileRequestDTO;
use App\Domain\Services\Customer\CustomerServiceInterface;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdateProfileAction
{
    public function __construct(
        private readonly CustomerServiceInterface $customerService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        // Handle file upload separately
        if (isset($data['image_file']) && $data['image_file'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image_file'] = $data['image_file'];
        }

        $dto = UpdateProfileRequestDTO::fromArray($data);
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $result = $this->customerService->updateProfile($dto, $user);

        return response()->json(new CustomerResource($result));
    }
}
