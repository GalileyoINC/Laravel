<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Domain\Services\Users\UsersServiceInterface;
use App\Models\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class GetUsersListAction
{
    public function __construct(
        private readonly UsersServiceInterface $usersService
    ) {}

    /**
     * @return LengthAwarePaginator<int, \App\Models\User\User>
     */
    public function execute(
        int $page = 1,
        int $pageSize = 20,
        ?string $search = null,
        ?int $status = null,
        ?int $role = null,
        ?bool $isInfluencer = null,
        string $sortBy = 'created_at',
        string $sortOrder = 'desc',
        ?bool $validEmailOnly = false
    ): LengthAwarePaginator {
        // Get authenticated user, but only if it's a User (not Staff)
        $user = Auth::guard('web')->check() ? Auth::guard('web')->user() : null;

        return $this->usersService->getUsersList($page, $pageSize, $search, $status, $role, $isInfluencer, $sortBy, $sortOrder, $user);
    }
}
