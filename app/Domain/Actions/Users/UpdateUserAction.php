<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Domain\DTOs\Users\UpdateUserDTO;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function execute(UpdateUserDTO $dto): User
    {
        $user = User::findOrFail($dto->userId);

        $data = [
            'first_name' => $dto->firstName,
            'last_name' => $dto->lastName,
            'email' => $dto->email,
            'country' => $dto->country,
            'state' => $dto->state,
            'zip' => $dto->zip,
            'city' => $dto->city,
        ];

        if ($dto->role !== null) {
            $data['role'] = $dto->role;
        }
        if ($dto->status !== null) {
            $data['status'] = $dto->status;
        }
        if (! empty($dto->password)) {
            $data['password_hash'] = Hash::make($dto->password);
        }

        $user->update($data);

        return $user->fresh();
    }
}
