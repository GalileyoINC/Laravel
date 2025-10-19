<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Podcast;

use Illuminate\Http\UploadedFile;

readonly class PodcastUpdateDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $url,
        public string $type,
        public ?UploadedFile $image = null,
    ) {}
}
