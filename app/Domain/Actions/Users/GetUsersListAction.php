<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Domain\Services\Users\UsersServiceInterface;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetUsersListAction
{
    public function __construct(
        private readonly UsersServiceInterface $usersService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @return LengthAwarePaginator<int, \App\Models\User\User>
     */
    public function execute(array $data): LengthAwarePaginator
    {
        try {
            $page = $data['page'] ?? 1;
            $pageSize = $data['page_size'] ?? 20;
            $search = $data['search'] ?? null;
            $status = $data['status'] ?? null;
            $role = $data['role'] ?? null;
            $isInfluencer = $data['is_influencer'] ?? null;
            $sortBy = $data['sort_by'] ?? 'created_at';
            $sortOrder = $data['sort_order'] ?? 'desc';

            $user = Auth::user();

            return $this->usersService->getUsersList($page, $pageSize, $search, $status, $role, $isInfluencer, $sortBy, $sortOrder, $user);

        } catch (Exception $e) {
            Log::error('GetUsersListAction error: '.$e->getMessage());

            // On error, rethrow to be handled upstream
            throw $e;
        }
    }
}
