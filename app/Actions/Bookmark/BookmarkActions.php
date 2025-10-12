<?php

namespace App\Actions\Bookmark;

use App\DTOs\Bookmark\BookmarkRequestDTO;
use App\DTOs\Bookmark\BookmarkListRequestDTO;
use App\Services\Bookmark\BookmarkServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetBookmarksAction
{
    public function __construct(
        private BookmarkServiceInterface $bookmarkService
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

        } catch (\Exception $e) {
            Log::error('GetBookmarksAction error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}

class CreateBookmarkAction
{
    public function __construct(
        private BookmarkServiceInterface $bookmarkService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = BookmarkRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['Invalid bookmark request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $user = Auth::user();
            $result = $this->bookmarkService->createBookmark($dto, $user);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            Log::error('CreateBookmarkAction error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }
}

class DeleteBookmarkAction
{
    public function __construct(
        private BookmarkServiceInterface $bookmarkService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = BookmarkRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['Invalid bookmark request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $user = Auth::user();
            $result = $this->bookmarkService->deleteBookmark($dto, $user);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            Log::error('DeleteBookmarkAction error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }
}
