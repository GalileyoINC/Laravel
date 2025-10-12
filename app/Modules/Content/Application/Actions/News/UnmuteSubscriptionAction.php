<?php

declare(strict_types=1);

namespace App\Modules\Content\Application\Actions\News;

use App\DTOs\News\MuteSubscriptionRequestDTO;
use App\Http\Resources\SuccessResource;
use App\Services\News\NewsServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UnmuteSubscriptionAction
{
    public function __construct(
        private readonly NewsServiceInterface $newsService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = MuteSubscriptionRequestDTO::fromArray($data);
            $user = Auth::user();

            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $result = $this->newsService->unmuteSubscription($dto, $user);

            return response()->json(new SuccessResource($result));

        } catch (Exception $e) {
            Log::error('UnmuteSubscriptionAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
