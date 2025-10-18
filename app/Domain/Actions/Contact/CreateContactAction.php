<?php

declare(strict_types=1);

namespace App\Domain\Actions\Contact;

use App\Domain\DTOs\Contact\CreateContactDTO;
use App\Domain\Services\Contact\ContactServiceInterface;
use App\Models\Communication\Contact;

class CreateContactAction
{
    public function __construct(
        private readonly ContactServiceInterface $contactService
    ) {}

    public function execute(CreateContactDTO $dto): Contact
    {
        return $this->contactService->create($dto);
    }
}
