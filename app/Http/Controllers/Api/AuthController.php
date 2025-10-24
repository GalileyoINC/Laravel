<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Authentication\LoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

/**
 * Authentication controller with DDD structure
 *
 * Now controllers only handle HTTP concerns
 */
#[OA\Tag(name: 'Authentication', description: 'Authentication endpoints')]
class AuthController extends Controller
{
    public function __construct(
        private readonly LoginAction $loginAction
    ) {}

    /**
     * Login endpoint
     *
     * POST /api/v1/auth/login
     */
    #[OA\Post(
        path: '/api/v1/auth/login',
        description: 'Authenticate user and return Sanctum token',
        summary: 'User login',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                ]
            )
        ),
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'user_id', type: 'integer', example: 1),
                        new OA\Property(property: 'access_token', type: 'string', example: '1|abcdef123456'),
                        new OA\Property(
                            property: 'user_profile',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                                new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                                new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
                                new OA\Property(property: 'role', type: 'string', example: 'user'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Invalid credentials',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Invalid credentials'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->loginAction->execute($request->validated());
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

        $user = \App\Models\User\User::where('email', $request->email)->first();

        if (! $user || ! password_verify($request->password, (string) $user->password_hash)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Create Sanctum token
        $token = $user->createToken('web-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'user_id' => $user->id,
            'access_token' => $token,
            'user_profile' => [
                'id' => $user->id,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'role' => $user->role,
            ],
        ]);
    }

    /**
     * Test endpoint
     *
     * GET /api/v1/auth/test
     */
    #[OA\Get(
        path: '/api/v1/auth/test',
        description: 'Test endpoint to verify authentication module is working',
        summary: 'Test authentication module',
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Test successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Authentication module is working!'),
                            new OA\Property(property: 'time', type: 'string', example: '2024-01-15 10:30:00'),
                            new OA\Property(property: 'module', type: 'string', example: 'Authentication'),
                            new OA\Property(property: 'version', type: 'string', example: '1.0'),
                        ], type: 'object'),
                    ]
                )
            ),
        ]
    )]
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
