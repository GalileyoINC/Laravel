<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Subscription;

use Illuminate\Http\UploadedFile;

readonly class SubscriptionStoreRequestDTO
{
    public function __construct(
        public int $categoryId,
        public string $title,
        public ?int $percent,
        public ?string $alias,
        public ?string $description,
        public bool $isCustom,
        public bool $showReactions,
        public bool $showComments,
        public ?UploadedFile $imageFile = null,
    ) {}
}
