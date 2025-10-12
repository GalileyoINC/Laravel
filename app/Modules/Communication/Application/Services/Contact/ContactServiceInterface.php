<?php

declare(strict_types=1);

namespace App\Services\Contact;

use App\DTOs\Contact\ContactListRequestDTO;
use App\Models\Communication\Contact;

interface ContactServiceInterface
{
    public function getList(ContactListRequestDTO $dto): array;

    public function getById(int $id): Contact;

    public function markAsDeleted(int $id): void;
}
