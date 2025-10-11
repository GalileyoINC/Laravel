<?php

namespace App\DTOs\News;

class GetNewsByFollowerListRequestDTO
{
    public function __construct(
        public string $id_follower_list,
        public int $page = 1,
        public int $page_size = 20
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id_follower_list: $data['id_follower_list'] ?? '',
            page: (int) ($data['page'] ?? 1),
            page_size: (int) ($data['page_size'] ?? 20)
        );
    }

    public function validate(): bool
    {
        return !empty($this->id_follower_list);
    }
}
