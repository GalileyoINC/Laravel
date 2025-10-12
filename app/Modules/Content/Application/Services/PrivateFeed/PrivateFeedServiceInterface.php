<?php

declare(strict_types=1);

namespace App\Services\PrivateFeed;

use App\DTOs\PrivateFeed\PrivateFeedCreateRequestDTO;
use App\DTOs\PrivateFeed\PrivateFeedListRequestDTO;
use App\Models\User\User;

/**
 * PrivateFeed service interface
 */
interface PrivateFeedServiceInterface
{
    /**
     * Get private feed list
     */
    public function getPrivateFeedList(PrivateFeedListRequestDTO $dto, User $user): array;

    /**
     * Create private feed
     *
     * @return mixed
     */
    public function createPrivateFeed(PrivateFeedCreateRequestDTO $dto, User $user);

    /**
     * Update private feed
     *
     * @return mixed
     */
    public function updatePrivateFeed(int $id, PrivateFeedCreateRequestDTO $dto, User $user);

    /**
     * Delete private feed
     */
    public function deletePrivateFeed(int $id, User $user): bool;
}
