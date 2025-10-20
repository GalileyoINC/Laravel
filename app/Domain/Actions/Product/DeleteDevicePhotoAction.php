<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\DevicePhotoDeleteDTO;
use App\Models\Content\ProductPhoto;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class DeleteDevicePhotoAction
{
    public function execute(DevicePhotoDeleteDTO $dto): bool
    {
        /** @var ProductPhoto $photo */
        $photo = ProductPhoto::query()->find($dto->photoId);
        if (! $photo) {
            return false;
        }

        $deletedFiles = true;
        $disk = config('filesystems.default');

        // Collect paths from folder_name and sizes json structure if present
        $paths = [];
        if (! empty($photo->folder_name)) {
            $paths[] = $photo->folder_name; // may be a dir; ignore if not file
        }
        if (is_array($photo->sizes)) {
            foreach ($photo->sizes as $size) {
                if (! empty($size['path'])) {
                    $paths[] = (string) $size['path'];
                }
                if (! empty($size['thumbnail'])) {
                    $paths[] = (string) $size['thumbnail'];
                }
                if (! empty($size['url'])) {
                    // skip URLs
                }
            }
        }

        foreach (array_unique($paths) as $path) {
            try {
                if ($path && ! str_starts_with($path, 'http')) {
                    Storage::disk($disk)->delete($path);
                }
            } catch (Throwable) {
                $deletedFiles = false; // continue, but report overall false if any delete failed
            }
        }

        $photo->delete();

        return $deletedFiles;
    }
}
