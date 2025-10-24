<?php

declare(strict_types=1);

namespace App\Domain\Services\Users;

use App\Domain\DTOs\Users\CreateUserDTO;
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
    public function getUsersList(int $page, int $pageSize, ?string $search, ?int $status, ?string $role, ?bool $isInfluencer, string $sortBy, string $sortOrder, ?User $user): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        try {
            $query = User::query()->with(['phoneNumbers']);

            // Apply filters
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', '%'.$search.'%')
                        ->orWhere('last_name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            }

            if ($role !== null) {
                $query->where('role', $role);
            }

            if ($status !== null) {
                $query->where('status', $status);
            }

            if ($isInfluencer !== null) {
                $query->where('is_influencer', $isInfluencer);
            }

            // Apply pagination and return paginator
            return $query->orderBy($sortBy, $sortOrder)
                ->paginate($pageSize, ['*'], 'page', $page);

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
