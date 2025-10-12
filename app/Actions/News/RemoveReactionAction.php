<?php

declare(strict_types=1);

namespace App\Actions\News;

use App\DTOs\News\SetReactionRequestDTO;
use App\Http\Resources\NewsResource;
use App\Services\News\NewsServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RemoveReactionAction
{
    public function __construct(
        private readonly NewsServiceInterface $newsService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = SetReactionRequestDTO::fromArray($data);
            $user = Auth::user();

            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $result = $this->newsService->removeReaction($dto, $user);

            return response()->json(new NewsResource($result));

        } catch (Exception $e) {
            Log::error('RemoveReactionAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
