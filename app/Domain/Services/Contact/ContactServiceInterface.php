<?php

declare(strict_types=1);

namespace App\Domain\Services\Contact;

use App\Domain\DTOs\Contact\ContactListRequestDTO;
use App\Domain\DTOs\Contact\CreateContactDTO;
use App\Models\Communication\Contact;

interface ContactServiceInterface
{
    public function getList(ContactListRequestDTO $dto): array;

    public function getById(int $id): Contact;

    public function create(CreateContactDTO $dto): Contact;

    public function markAsDeleted(int $id): void;
}
