<?php

declare(strict_types=1);

namespace App\Domain\Actions\Bookmark;

use App\Domain\Services\Bookmark\BookmarkServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetBookmarksAction
{
    public function __construct(
        private readonly BookmarkServiceInterface $bookmarkService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $page = $data['page'] ?? 1;
        $limit = $data['limit'] ?? 20;
        $search = $data['search'] ?? null;
        $sortBy = $data['sort_by'] ?? 'created_at';
        $sortOrder = $data['sort_order'] ?? 'desc';

        $user = Auth::user();
        $results = $this->bookmarkService->getBookmarks($page, $limit, $search, $sortBy, $sortOrder, $user);

        return response()->json([
            'status' => 'success',
            'data' => $results,
        ]);
    }
}
