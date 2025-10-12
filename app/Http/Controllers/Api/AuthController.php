<?php

namespace App\Http\Controllers\Api;

use App\Actions\Authentication\LoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Authentication controller with DDD structure
 *
 * Now controllers only handle HTTP concerns
 */
class AuthController extends Controller
{
    public function __construct(
        private LoginAction $loginAction
    ) {}

    /**
     * Login endpoint
     *
     * POST /api/v1/auth/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            // Request validation is handled automatically by LoginRequest
            $result = $this->loginAction->execute($request->validated());

            // Return the result directly since LoginAction already returns JsonResponse
            return $result;

        } catch (\Exception $e) {
            // Use ErrorResource for consistent error format
            return response()->json(new ErrorResource([
                'message' => $e->getMessage(),
                'code' => 500,
                'trace_id' => uniqid(),
            ]), 500);
        }
    }

    /**
     * Simple web login endpoint
     *
     * POST /api/auth/login
     */
    public function webLogin(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (! $user || ! password_verify($request->password, $user->password_hash)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Create Sanctum token
        $token = $user->createToken('web-token')->plainTextToken;

        return response()->json([
            'user_id' => $user->id,
            'access_token' => $token,
            'user_profile' => [
                'id' => $user->id,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ],
        ]);
    }

    /**
     * Test endpoint
     *
     * GET /api/v1/auth/test
     */
    public function test(): JsonResponse
    {
        return response()->json(new SuccessResource([
            'message' => 'Authentication module is working!',
            'time' => now()->format('Y-m-d H:i:s'),
            'module' => 'Authentication',
            'version' => '1.0',
        ]));
    }

    /**
     * Signup endpoint
     *
     * POST /api/v1/default/signup
     */
    public function signup(Request $request): JsonResponse
    {
        return response()->json(new SuccessResource([
            'message' => 'Signup endpoint - to be implemented',
            'time' => now()->format('Y-m-d H:i:s'),
            'module' => 'Authentication',
            'version' => '1.0',
        ]));
    }

    /**
     * News by subscription endpoint
     *
     * POST /api/v1/default/news-by-subscription
     */
    public function newsBySubscription(Request $request): JsonResponse
    {
        return response()->json(new SuccessResource([
            'message' => 'News by subscription endpoint - to be implemented',
            'time' => now()->format('Y-m-d H:i:s'),
            'module' => 'Authentication',
            'version' => '1.0',
        ]));
    }

    /**
     * Handle CORS preflight requests
     */
    public function options(): JsonResponse
    {
        return response()->json([]);
    }
}
