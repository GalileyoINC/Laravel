<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Services\Users;

use App\DTOs\Users\UsersListRequestDTO;
use App\Models\User\User;

interface UsersServiceInterface
{
    /**
     * Get list of users
     */
    public function getUsersList(UsersListRequestDTO $dto, ?User $user);
}
