<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Podcast;

use Illuminate\Http\UploadedFile;

readonly class PodcastCreateDTO
{
    public function __construct(
        public string $title,
        public string $url,
        public string $type,
        public ?UploadedFile $image = null,
    ) {}
}
