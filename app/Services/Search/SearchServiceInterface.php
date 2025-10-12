<?php

namespace App\Services\Search;

use App\DTOs\Search\SearchRequestDTO;
use App\Models\User;

interface SearchServiceInterface
{
    /**
     * Search for content
     */
    public function search(SearchRequestDTO $dto, ?User $user);
}
