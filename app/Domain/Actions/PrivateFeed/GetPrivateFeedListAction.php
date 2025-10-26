<?php

declare(strict_types=1);

namespace App\Domain\Actions\PrivateFeed;

use App\Domain\DTOs\PrivateFeed\PrivateFeedListRequestDTO;
use App\Domain\Services\PrivateFeed\PrivateFeedServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetPrivateFeedListAction
{
    public function __construct(
        private readonly PrivateFeedServiceInterface $privateFeedService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $dto = PrivateFeedListRequestDTO::fromArray($data);
        if (! $dto->validate()) {
            return response()->json([
                'status' => 'error',
                'errors' => ['Invalid private feed list request'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'status' => 'error',
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $privateFeeds = $this->privateFeedService->getPrivateFeedList($dto, $user);

        return response()->json([
            'status' => 'success',
            'data' => [
                'list' => $privateFeeds,
                'count' => count($privateFeeds),
                'page' => 1,
                'page_size' => count($privateFeeds),
            ],
        ]);
    }
}
