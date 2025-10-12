<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\PrivateFeed\GetPrivateFeedListAction;
use App\DTOs\PrivateFeed\PrivateFeedCreateRequestDTO;
use App\Http\Controllers\Controller;
use App\Services\PrivateFeed\PrivateFeedServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Refactored PrivateFeed Controller using DDD Actions
 * Handles private feed content management: creation, invites, followers
 */
class PrivateFeedController extends Controller
{
    public function __construct(
        private readonly GetPrivateFeedListAction $getPrivateFeedListAction,
        private readonly PrivateFeedServiceInterface $privateFeedService
    ) {}

    /**
     * Get private feed list (POST /api/v1/private-feed/index)
     */
    public function index(Request $request): JsonResponse
    {
        return $this->getPrivateFeedListAction->execute($request->all());
    }

    /**
     * Create private feed (POST /api/v1/private-feed/create)
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $dto = PrivateFeedCreateRequestDTO::fromRequest($request);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid private feed creation request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $privateFeed = $this->privateFeedService->createPrivateFeed($dto, $user);

            return response()->json($privateFeed->toArray());

        } catch (Exception $e) {
            Log::error('PrivateFeedController create error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Update private feed (POST /api/v1/private-feed/update)
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $id = $request->input('id');
            if (! $id) {
                return response()->json([
                    'errors' => ['ID is required'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $dto = PrivateFeedCreateRequestDTO::fromRequest($request);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid private feed update request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $privateFeed = $this->privateFeedService->updatePrivateFeed($id, $dto, $user);

            return response()->json($privateFeed->toArray());

        } catch (Exception $e) {
            Log::error('PrivateFeedController update error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Delete private feed (POST /api/v1/private-feed/delete)
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $id = $request->input('id');
            if (! $id) {
                return response()->json([
                    'errors' => ['ID is required'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $success = $this->privateFeedService->deletePrivateFeed($id, $user);

            if ($success) {
                return response()->json(['success' => true]);
            }

            return response()->json([
                'error' => 'Failed to delete private feed',
                'code' => 400,
            ], 400);

        } catch (Exception $e) {
            Log::error('PrivateFeedController delete error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
