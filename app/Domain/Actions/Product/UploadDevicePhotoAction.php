<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\DevicePhotoUploadDTO;
use Illuminate\Support\Facades\Storage;

final class UploadDevicePhotoAction
{
    public function execute(DevicePhotoUploadDTO $dto): array
    {
        // Persist file
        $path = $dto->file->store('devices', 'public');

        // Return response structure expected by the frontend widget
        return [
            'url' => Storage::url($path),
            'path' => $path,
            'caption' => $dto->file->getClientOriginalName(),
        ];
    }
}
