<?php

declare(strict_types=1);

namespace App\Domain\Actions\Bookmark;

use App\Domain\DTOs\Bookmark\BookmarkListRequestDTO;
use App\Domain\Services\Bookmark\BookmarkServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetBookmarksAction
{
    public function __construct(
        private readonly BookmarkServiceInterface $bookmarkService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = BookmarkListRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['Invalid bookmark list request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $user = Auth::user();
            $results = $this->bookmarkService->getBookmarks($dto, $user);

            return response()->json([
                'status' => 'success',
                'data' => $results,
            ]);

        } catch (Exception $e) {
            Log::error('GetBookmarksAction error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
