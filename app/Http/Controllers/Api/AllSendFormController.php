<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\AllSendForm\GetAllSendOptionsAction;
use App\Domain\DTOs\AllSendForm\AllSendBroadcastRequestDTO;
use App\Domain\DTOs\AllSendForm\AllSendImageUploadRequestDTO;
use App\Domain\Services\AllSendForm\AllSendFormServiceInterface;
use App\Http\Controllers\Controller;
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
    }

    /**
     * Upload image for broadcast (POST /api/v1/all-send-form/image-upload)
     */
    public function imageUpload(Request $request): JsonResponse
    {
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
    }

    /**
     * Delete image (POST /api/v1/all-send-form/image-delete)
     */
    public function imageDelete(Request $request): JsonResponse
    {
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
    }
}
