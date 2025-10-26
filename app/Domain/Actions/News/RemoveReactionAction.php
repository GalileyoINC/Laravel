<?php

declare(strict_types=1);

namespace App\Domain\Actions\News;

use App\Domain\DTOs\News\ReactionRequestDTO;
use App\Domain\Services\News\NewsServiceInterface;
use App\Http\Resources\NewsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RemoveReactionAction
{
    public function __construct(
        private readonly NewsServiceInterface $newsService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $dto = ReactionRequestDTO::fromArray($data);
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $result = $this->newsService->removeReaction($dto, $user);

        return response()->json(new NewsResource($result));
    }
}
