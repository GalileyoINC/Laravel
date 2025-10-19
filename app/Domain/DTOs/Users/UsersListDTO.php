<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Users;

use Illuminate\Http\Request;

class UsersListDTO
{
    public function __construct(
        public readonly ?string $search,
        public readonly int $limit
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->has('search') ? (string) $request->get('search') : null,
            limit: (int) ($request->get('limit', 20))
        );
    }
}
