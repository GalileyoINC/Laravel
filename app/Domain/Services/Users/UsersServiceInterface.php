<?php

declare(strict_types=1);

namespace App\Domain\Services\Users;

use App\Domain\DTOs\Users\CreateUserDTO;
use App\Domain\DTOs\Users\UsersListRequestDTO;
use App\Models\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UsersServiceInterface
{
    /**
     * Get list of users
     */
    public function getUsersList(UsersListRequestDTO $dto, ?User $user): LengthAwarePaginator;

    /**
     * Create a new user
     */
    public function create(CreateUserDTO $dto): User;
}
