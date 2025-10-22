<?php

declare(strict_types=1);

namespace App\Domain\Services\Authentication;

use App\Domain\DTOs\Authentication\AuthResponseDTO;
use App\Domain\DTOs\Authentication\LoginRequestDTO;
use App\Models\Device\Device;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Authentication service implementation
 *
 * Bridge between DDD structure and existing authentication logic
 */
class AuthService implements AuthServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function authenticate(LoginRequestDTO $loginDto): ?AuthResponseDTO
    {
        try {
            // Find user by email
            $user = User::where('email', $loginDto->email)->first();

            if (! $user) {
                return null;
            }

            // Verify password (assuming password_hash is stored)
            if (! password_verify($loginDto->password, (string) $user->password_hash)) {
                return null;
            }

            // Create Sanctum token
            $token = $user->createToken('api-token', ['*'], now()->addHours(24));

            // Create or update device record (without access_token)
            $device = Device::updateOrCreate(
                ['id_user' => $user->id],
                [
                    'uuid' => $loginDto->device['device_uuid'] ?? 'unknown',
                    'os' => $loginDto->device['device_os'] ?? 'unknown',
                    'params' => json_encode($loginDto->device),
                    'updated_at' => now(),
                ]
            );

            // Return authentication response
            return new AuthResponseDTO(
                user_id: $user->id,
                access_token: $token->plainTextToken,
                refresh_token: '', // Refresh tokens can be added later
                expires_in: 3600,  // Default expiration
                user_profile: [
                    'id' => $user->id,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'role' => $user->role,
                ]
            );

        } catch (Exception $e) {
            Log::error('Authentication error: '.$e->getMessage());

            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function refreshToken(string $refreshToken): ?AuthResponseDTO
    {
        // This can be implemented later with proper token refresh logic
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function logout(string $accessToken): bool
    {
        try {
            // Find token by plain text token and revoke it
            $token = \Laravel\Sanctum\PersonalAccessToken::findToken($accessToken);

            if ($token) {
                $token->delete();

                return true;
            }

            return false;
        } catch (Exception $e) {
            Log::error('Logout error: '.$e->getMessage());

            return false;
        }
    }
}
