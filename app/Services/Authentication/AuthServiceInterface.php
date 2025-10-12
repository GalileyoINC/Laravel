<?php

declare(strict_types=1);

namespace App\Services\Authentication;

use App\DTOs\Authentication\AuthResponseDTO;
use App\DTOs\Authentication\LoginRequestDTO;

/**
 * Authentication service interface
 */
interface AuthServiceInterface
{
    /**
     * Authenticate user with login credentials
     */
    public function authenticate(LoginRequestDTO $loginDto): ?AuthResponseDTO;

    /**
     * Refresh access token
     */
    public function refreshToken(string $refreshToken): ?AuthResponseDTO;

    /**
     * Logout user by invalidating access token
     */
    public function logout(string $accessToken): bool;
}
