<?php

declare(strict_types=1);

namespace App\Domain\DTOs\News;

use Illuminate\Http\UploadedFile;

readonly class NewsUpdateDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $title = null,
        public ?string $metaKeywords = null,
        public ?string $metaDescription = null,
        public int $status = 0,
        public ?UploadedFile $image = null,
    ) {}
}
