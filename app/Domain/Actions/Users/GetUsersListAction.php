<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Domain\DTOs\Users\UsersListRequestDTO;
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
            $dto = UsersListRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                // Fallback to empty paginator on invalid input
                return $this->usersService->getUsersList(UsersListRequestDTO::fromArray(['page' => 1, 'page_size' => 1]), null);
            }

            $user = Auth::user();

            return $this->usersService->getUsersList($dto, $user);

        } catch (Exception $e) {
            Log::error('GetUsersListAction error: '.$e->getMessage());

            // On error, rethrow to be handled upstream
            throw $e;
        }
    }
}
