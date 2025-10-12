<?php

declare(strict_types=1);

namespace App\Modules\User\Application\DTOs\Users;

use Illuminate\Http\Request;

/**
 * DTO for Users list requests
 */
class UsersListRequestDTO
{
    public function __construct(
        public readonly ?int $page = 1,
        public readonly ?int $pageSize = 50,
        public readonly ?string $search = null,
        public readonly ?int $role = null,
        public readonly ?bool $validEmailOnly = true
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            page: isset($data['page']) ? (int) $data['page'] : 1,
            pageSize: isset($data['page_size']) ? (int) $data['page_size'] : 50,
            search: $data['search'] ?? null,
            role: isset($data['role']) ? (int) $data['role'] : null,
            validEmailOnly: isset($data['valid_email_only']) ? (bool) $data['valid_email_only'] : true
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            page: $request->input('page', 1),
            pageSize: $request->input('page_size', 50),
            search: $request->input('search'),
            role: $request->input('role'),
            validEmailOnly: $request->input('valid_email_only', true)
        );
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'page_size' => $this->pageSize,
            'search' => $this->search,
            'role' => $this->role,
            'valid_email_only' => $this->validEmailOnly,
        ];
    }

    public function validate(): bool
    {
        return $this->page > 0 &&
               $this->pageSize > 0 &&
               $this->pageSize <= 100;
    }
}
