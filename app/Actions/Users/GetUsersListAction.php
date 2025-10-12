<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\DTOs\Users\UsersListRequestDTO;
use App\Services\Users\UsersServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetUsersListAction
{
    public function __construct(
        private readonly UsersServiceInterface $usersService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = UsersListRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['Invalid users request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $user = Auth::user();
            $users = $this->usersService->getUsersList($dto, $user);

            return response()->json([
                'status' => 'success',
                'data' => $users->toArray(),
            ]);

        } catch (Exception $e) {
            Log::error('GetUsersListAction error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
