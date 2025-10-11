<?php

namespace App\Services\Authentication;

use App\DTOs\Authentication\LoginRequestDTO;
use App\DTOs\Authentication\AuthResponseDTO;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            
            if (!$user) {
                return null;
            }

            // Verify password (assuming password_hash is stored)
            if (!password_verify($loginDto->password, $user->password_hash)) {
                return null;
            }

            // Generate access token
            $accessToken = Str::random(64);
            
            // Create or update device record
            $device = Device::updateOrCreate(
                ['id_user' => $user->id],
                [
                    'access_token' => $accessToken,
                    'uuid' => $loginDto->device['device_uuid'] ?? 'unknown',
                    'os' => $loginDto->device['device_os'] ?? 'unknown',
                    'params' => json_encode($loginDto->device),
                    'updated_at' => now()
                ]
            );

            // Return authentication response
            return new AuthResponseDTO(
                user_id: $user->id,
                access_token: $accessToken,
                refresh_token: '', // Refresh tokens can be added later
                expires_in: 3600,  // Default expiration
                user_profile: [
                    'id' => $user->id,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name
                ]
            );

        } catch (\Exception $e) {
            Log::error('Authentication error: ' . $e->getMessage());
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
            // Find device by token and delete it
            $device = Device::where('access_token', $accessToken)->first();
            
            if ($device) {
                $device->delete();
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return false;
        }
    }
}
