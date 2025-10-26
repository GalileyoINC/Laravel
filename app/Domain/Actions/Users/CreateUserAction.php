<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Domain\DTOs\Users\CreateUserDTO;
use App\Domain\Services\Users\UsersServiceInterface;
use Illuminate\Http\JsonResponse;

class CreateUserAction
{
    public function __construct(
        private readonly UsersServiceInterface $usersService
    ) {}

    public function execute(CreateUserDTO $dto): JsonResponse
    {
        $user = $this->usersService->create($dto);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user,
        ]);
    }
}
