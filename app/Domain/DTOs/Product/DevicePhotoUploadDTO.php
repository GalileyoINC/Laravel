<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Product;

use Illuminate\Http\UploadedFile;

readonly class DevicePhotoUploadDTO
{
    public function __construct(
        public int $deviceId,
        public UploadedFile $file,
    ) {}
}
