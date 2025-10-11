<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Authentication\LoginAction;
use App\Http\Resources\AuthenticationResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Requests\Authentication\LoginRequest;
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
                'trace_id' => uniqid()
            ]), 500);
        }
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
            'version' => '1.0'
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
            'version' => '1.0'
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
            'version' => '1.0'
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