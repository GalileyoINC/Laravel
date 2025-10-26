<?php

declare(strict_types=1);

namespace App\Domain\Actions\Contact;

use App\Domain\Services\Contact\ContactServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetContactListAction
{
    public function __construct(
        private readonly ContactServiceInterface $contactService
    ) {}

    /**
     * @return LengthAwarePaginator<int, mixed>
     */
    public function execute(
        int $page = 1,
        int $limit = 20,
        ?string $search = null,
        int $status = 1
    ): LengthAwarePaginator {
        return $this->contactService->getList($page, $limit, $search, $status);
    }
}
