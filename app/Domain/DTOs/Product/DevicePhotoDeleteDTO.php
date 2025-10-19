<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Product;

readonly class DevicePhotoDeleteDTO
{
    public function __construct(
        public string $photoId,
    ) {}
}
