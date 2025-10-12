<?php

declare(strict_types=1);

namespace App\Actions\Bookmark;

use App\DTOs\Bookmark\BookmarkRequestDTO;
use App\Services\Bookmark\BookmarkServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CreateBookmarkAction
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
            $result = $this->bookmarkService->createBookmark($dto, $user);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            Log::error('CreateBookmarkAction error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }
}
