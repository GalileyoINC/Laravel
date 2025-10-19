<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Content;

use Illuminate\Http\UploadedFile;

readonly class PageImageUploadDTO
{
    public function __construct(
        public int $pageId,
        public UploadedFile $file,
    ) {}
}
