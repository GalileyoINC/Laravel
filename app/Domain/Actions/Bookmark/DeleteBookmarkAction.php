<?php

declare(strict_types=1);

namespace App\Domain\Actions\Bookmark;

use App\Actions\Bookmark\Log;
use App\Domain\DTOs\Bookmark\BookmarkRequestDTO;
use App\Domain\Services\Bookmark\BookmarkServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DeleteBookmarkAction
{
    public function __construct(
        private readonly BookmarkServiceInterface $bookmarkService
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

        } catch (Exception $e) {
            Log::error('DeleteBookmarkAction error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }
}
