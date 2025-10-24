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
     * @param  array<string, mixed>  $data
     * @return LengthAwarePaginator<int, mixed>
     */
    public function execute(array $data): LengthAwarePaginator
    {
        $page = $data['page'] ?? 1;
        $limit = $data['limit'] ?? 20;
        $search = $data['search'] ?? null;
        $status = $data['status'] ?? 1;

        return $this->contactService->getList($page, $limit, $search, $status);
    }
}
