<?php

declare(strict_types=1);

namespace App\Domain\Services\Contact;

use App\Domain\DTOs\Contact\CreateContactDTO;
use App\Models\Communication\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ContactServiceInterface
{
    /**
     * @return LengthAwarePaginator<int, Contact>
     */
    public function getList(int $page, int $limit, ?string $search, int $status): LengthAwarePaginator;

    public function getById(int $id): Contact;

    public function create(CreateContactDTO $dto): Contact;

    public function markAsDeleted(int $id): void;
}
