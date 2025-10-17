<?php

declare(strict_types=1);

namespace App\Domain\Services\Search;

use App\Domain\DTOs\Search\SearchRequestDTO;
use App\Models\User\User;

interface SearchServiceInterface
{
    /**
     * Search for content
     */
    public function search(SearchRequestDTO $dto, ?User $user);
}
