<?php

declare(strict_types=1);

namespace App\Domain\Services\Users;

use App\Domain\DTOs\Users\CreateUserDTO;
use App\Domain\DTOs\Users\UsersListRequestDTO;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

        } catch (Exception $e) {
            Log::error('UsersService getUsersList error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create(CreateUserDTO $dto): User
    {
        try {
            return User::create([
                'first_name' => $dto->firstName,
                'last_name' => $dto->lastName,
                'email' => $dto->email,
                'password_hash' => Hash::make($dto->password),
                'auth_key' => Str::random(32),
                'role' => $dto->role,
                'status' => $dto->status,
                'country' => $dto->country,
                'zip' => $dto->zip,
                'state' => $dto->state,
                'city' => $dto->city,
                'is_valid_email' => false,
                'is_receive_subscribe' => true,
                'is_receive_list' => true,
                'bonus_point' => 0,
            ]);

        } catch (Exception $e) {
            Log::error('UsersService create error: '.$e->getMessage());
            throw $e;
        }
    }
}
