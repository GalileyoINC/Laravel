<?php

namespace App\Services\Users;

use App\DTOs\Users\UsersListRequestDTO;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Users service implementation
 */
class UsersService implements UsersServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getUsersList(UsersListRequestDTO $dto, ?User $user)
    {
        try {
            $query = User::select('id', 'email', 'first_name', 'last_name', 'role');

            // Apply filters
            if ($dto->validEmailOnly) {
                $query->where('is_valid_email', true);
            }

            if ($dto->role !== null) {
                $query->where('role', $dto->role);
            }

            if ($dto->search) {
                $query->where(function ($q) use ($dto) {
                    $q->where('first_name', 'like', '%'.$dto->search.'%')
                        ->orWhere('last_name', 'like', '%'.$dto->search.'%')
                        ->orWhere('email', 'like', '%'.$dto->search.'%');
                });
            }

            // Apply pagination
            $offset = ($dto->page - 1) * $dto->pageSize;
            $users = $query->orderBy('first_name')
                ->limit($dto->pageSize)
                ->offset($offset)
                ->get();

            return $users;

        } catch (\Exception $e) {
            Log::error('UsersService getUsersList error: '.$e->getMessage());
            throw $e;
        }
    }
}
