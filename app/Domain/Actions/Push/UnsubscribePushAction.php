<?php

declare(strict_types=1);

namespace App\Domain\Actions\Push;

use App\Domain\Services\Push\PushServiceInterface;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;

class UnsubscribePushAction
{
    public function __construct(
        private readonly PushServiceInterface $pushService
    ) {
    }

    public function execute(string $endpoint): bool
    {
        /** @var User $user */
        $user = Auth::user();

        return $this->pushService->unsubscribe($user, $endpoint);
    }
}
