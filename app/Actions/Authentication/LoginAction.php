<?php

namespace App\Actions\Authentication;

use App\DTOs\Authentication\LoginRequestDTO;
use App\DTOs\Authentication\AuthResponseDTO;
use App\Services\Authentication\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Login action - handles user authentication
 * 
 * This action encapsulates the business logic for user login
 */
class LoginAction
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {}

    /**
     * Execute login action
     *
     * @param array $data Login data (email, password, device)
     * @return JsonResponse
     */
    public function execute(array $data): JsonResponse
    {
        try {
            // Create and validate DTO
            $loginDto = LoginRequestDTO::fromArray($data);
            
            // Authenticate user
            $authResponse = $this->authService->authenticate($loginDto);

            if (!$authResponse) {
                return response()->json([
                    'error' => 'Invalid credentials',
                    'code' => 401
                ], 401);
            }

            // Return successful authentication response
            return response()->json($authResponse->toArray());

        } catch (\Exception $e) {
            Log::error('Login action error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Login failed',
                'code' => 500
            ], 500);
        }
    }
}
