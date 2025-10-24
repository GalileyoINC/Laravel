<?php

declare(strict_types=1);

namespace App\Domain\Services\Users;

use App\Domain\DTOs\Users\CreateUserDTO;
use App\Models\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UsersServiceInterface
{
    /**
     * Get list of users
     *
     * @return LengthAwarePaginator<int, User>
     */
    public function getUsersList(int $page, int $pageSize, ?string $search, ?int $status, ?string $role, ?bool $isInfluencer, string $sortBy, string $sortOrder, ?User $user): LengthAwarePaginator;

    /**
     * Create a new user
     */
    public function create(CreateUserDTO $dto): User;
}
