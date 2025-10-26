<?php

declare(strict_types=1);

namespace App\Domain\Actions\News;

use App\Domain\DTOs\News\ReactionRequestDTO;
use App\Domain\Services\News\NewsServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SetReactionAction
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
        if (! $dto->validate()) {
            return response()->json([
                'errors' => ['Invalid reaction request'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $reaction = $this->newsService->setReaction($dto, $user);

        return response()->json($reaction->toArray());
    }
}
