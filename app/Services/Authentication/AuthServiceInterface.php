<?php

namespace App\Services\Authentication;

use App\DTOs\Authentication\LoginRequestDTO;
use App\DTOs\Authentication\AuthResponseDTO;

/**
 * Authentication service interface
 */
interface AuthServiceInterface
{
    /**
     * Authenticate user with login credentials
     *
     * @param LoginRequestDTO $loginDto
     * @return AuthResponseDTO|null
     */
    public function authenticate(LoginRequestDTO $loginDto): ?AuthResponseDTO;

    /**
     * Refresh access token
     *
     * @param string $refreshToken
     * @return AuthResponseDTO|null
     */
    public function refreshToken(string $refreshToken): ?AuthResponseDTO;

    /**
     * Logout user by invalidating access token
     *
     * @param string $accessToken
     * @return bool
     */
    public function logout(string $accessToken): bool;
}
