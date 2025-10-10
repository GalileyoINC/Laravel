<?php

namespace App\Services\PrivateFeed;

use App\DTOs\PrivateFeed\PrivateFeedListRequestDTO;
use App\DTOs\PrivateFeed\PrivateFeedCreateRequestDTO;
use App\Models\User;

/**
 * PrivateFeed service interface
 */
interface PrivateFeedServiceInterface
{
    /**
     * Get private feed list
     *
     * @param PrivateFeedListRequestDTO $dto
     * @param User $user
     * @return array
     */
    public function getPrivateFeedList(PrivateFeedListRequestDTO $dto, User $user): array;

    /**
     * Create private feed
     *
     * @param PrivateFeedCreateRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function createPrivateFeed(PrivateFeedCreateRequestDTO $dto, User $user);

    /**
     * Update private feed
     *
     * @param int $id
     * @param PrivateFeedCreateRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function updatePrivateFeed(int $id, PrivateFeedCreateRequestDTO $dto, User $user);

    /**
     * Delete private feed
     *
     * @param int $id
     * @param User $user
     * @return bool
     */
    public function deletePrivateFeed(int $id, User $user): bool;
}
