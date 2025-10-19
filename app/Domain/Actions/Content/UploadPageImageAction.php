<?php

declare(strict_types=1);

namespace App\Domain\Actions\Content;

use App\Domain\DTOs\Content\PageImageUploadDTO;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class UploadPageImageAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(PageImageUploadDTO $dto): array
    {
        $path = $dto->file->store('pages', 'public');

        if ($path === false) {
            throw new RuntimeException('Failed to store file');
        }

        return [
            'location' => Storage::url($path),
            'path' => $path,
        ];
    }
}
