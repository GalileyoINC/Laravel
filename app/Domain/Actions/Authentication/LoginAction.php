<?php

declare(strict_types=1);

namespace App\Domain\Actions\Authentication;

use App\Domain\DTOs\Authentication\LoginRequestDTO;
use App\Domain\Services\Authentication\AuthServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * Login action - handles user authentication
 *
 * This action encapsulates the business logic for user login
 */
class LoginAction
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    /**
     * Execute login action
     *
     * @param  array<string, mixed>  $data  Login data (email, password, device)
     */
    public function execute(array $data): JsonResponse
    {
        // Create and validate DTO
        $loginDto = LoginRequestDTO::fromArray($data);

        // Authenticate user
        $authResponse = $this->authService->authenticate($loginDto);

        if (! $authResponse) {
            return response()->json([
                'error' => 'Invalid credentials',
                'code' => 401,
            ], 401);
        }

        // Return successful authentication response
        return response()->json($authResponse->toArray());
    }
}
