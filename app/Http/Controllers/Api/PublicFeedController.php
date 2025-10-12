<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\PublicFeed\GetPublicFeedOptionsAction;
use App\DTOs\PublicFeed\PublicFeedImageUploadRequestDTO;
use App\DTOs\PublicFeed\PublicFeedPublishRequestDTO;
use App\Http\Controllers\Controller;
use App\Services\PublicFeed\PublicFeedServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Refactored PublicFeed Controller using DDD Actions
 * Handles public content publication: subscription targeting, messaging, image uploads
 */
class PublicFeedController extends Controller
{
    public function __construct(
        private readonly GetPublicFeedOptionsAction $getPublicFeedOptionsAction,
        private readonly PublicFeedServiceInterface $publicFeedService
    ) {}

    /**
     * Get public feed options (POST /api/v1/public-feed/get-options)
     */
    public function getOptions(Request $request): JsonResponse
    {
        return $this->getPublicFeedOptionsAction->execute($request->all());
    }

    /**
     * Send to public feeds (POST /api/v1/public-feed/send)
     */
    public function send(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $dto = PublicFeedPublishRequestDTO::fromRequest($request);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid public feed publish request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $result = $this->publicFeedService->publishToPublicFeeds($dto, $user);

            return response()->json($result);

        } catch (Exception $e) {
            Log::error('PublicFeedController send error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Upload image for public feed (POST /api/v1/public-feed/image-upload)
     */
    public function imageUpload(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $dto = PublicFeedImageUploadRequestDTO::fromRequest($request);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid image upload request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $result = $this->publicFeedService->uploadImage($dto, $user);

            return response()->json($result);

        } catch (Exception $e) {
            Log::error('PublicFeedController imageUpload error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
