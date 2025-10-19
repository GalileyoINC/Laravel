<?php

declare(strict_types=1);

namespace App\Domain\Actions\Content;

use App\Domain\DTOs\Content\PageImageUploadDTO;
use Illuminate\Support\Facades\Storage;

final class UploadPageImageAction
{
    public function execute(PageImageUploadDTO $dto): array
    {
        $path = $dto->file->store('pages', 'public');

        return [
            'location' => Storage::url($path),
            'path' => $path,
        ];
    }
}
