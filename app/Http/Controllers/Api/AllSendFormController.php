<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\AllSendForm\GetAllSendOptionsAction;
use App\DTOs\AllSendForm\AllSendBroadcastRequestDTO;
use App\DTOs\AllSendForm\AllSendImageUploadRequestDTO;
use App\Http\Controllers\Controller;
use App\Services\AllSendForm\AllSendFormServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Refactored AllSendForm Controller using DDD Actions
 * Handles content broadcasting: bulk messages, scheduling, file uploads
 */
class AllSendFormController extends Controller
{
    public function __construct(
        private readonly GetAllSendOptionsAction $getAllSendOptionsAction,
        private readonly AllSendFormServiceInterface $allSendFormService
    ) {}

    /**
     * Get broadcasting options (POST /api/v1/all-send-form/get-options)
     */
    public function getOptions(Request $request): JsonResponse
    {
        return $this->getAllSendOptionsAction->execute($request->all());
    }

    /**
     * Send broadcast message (POST /api/v1/all-send-form/send)
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

            $dto = AllSendBroadcastRequestDTO::fromRequest($request);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid broadcast request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $result = $this->allSendFormService->sendBroadcast($dto, $user);

            return response()->json($result);

        } catch (Exception $e) {
            Log::error('AllSendFormController send error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Upload image for broadcast (POST /api/v1/all-send-form/image-upload)
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

            $dto = AllSendImageUploadRequestDTO::fromRequest($request);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid image upload request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $result = $this->allSendFormService->uploadImage($dto, $user);

            return response()->json($result);

        } catch (Exception $e) {
            Log::error('AllSendFormController imageUpload error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Delete image (POST /api/v1/all-send-form/image-delete)
     */
    public function imageDelete(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $imageId = $request->input('id_image');
            if (! $imageId) {
                return response()->json([
                    'errors' => ['Image ID is required'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $result = $this->allSendFormService->deleteImage($imageId, $user);

            return response()->json($result);

        } catch (Exception $e) {
            Log::error('AllSendFormController imageDelete error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
