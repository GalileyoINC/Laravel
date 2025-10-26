<?php

declare(strict_types=1);

namespace App\Domain\Actions\Bookmark;

use App\Domain\DTOs\Bookmark\BookmarkRequestDTO;
use App\Domain\Services\Bookmark\BookmarkServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CreateBookmarkAction
{
    public function __construct(
        private readonly BookmarkServiceInterface $bookmarkService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
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
    }
}
