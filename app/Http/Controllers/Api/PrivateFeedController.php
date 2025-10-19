<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\PrivateFeed\GetPrivateFeedListAction;
use App\Domain\DTOs\PrivateFeed\PrivateFeedCreateRequestDTO;
use App\Domain\Services\PrivateFeed\PrivateFeedServiceInterface;
use App\Http\Controllers\Controller;
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
    }

    /**
     * Update private feed (POST /api/v1/private-feed/update)
     */
    public function update(Request $request): JsonResponse
    {
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
    }

    /**
     * Delete private feed (POST /api/v1/private-feed/delete)
     */
    public function delete(Request $request): JsonResponse
    {
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
    }
}
