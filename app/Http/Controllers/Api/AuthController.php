<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Authentication\LoginAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
    public function login(Request $request): JsonResponse
    {
        // Controller only handles HTTP - delegates to Action
        return $this->loginAction->execute($request->all());
    }

    /**
     * Test endpoint
     * 
     * GET /api/v1/auth/test
     */
    public function test(): JsonResponse
    {
        return response()->json([
            'message' => 'Authentication module is working!',
            'time' => now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Handle CORS preflight requests
     */
    public function options(): JsonResponse
    {
        return response()->json([]);
    }
}