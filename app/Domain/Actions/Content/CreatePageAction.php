<?php

declare(strict_types=1);

namespace App\Domain\Actions\Content;

use App\Domain\DTOs\Content\PageCreateDTO;
use App\Models\Content\Page;

final class CreatePageAction
{
    public function execute(PageCreateDTO $dto): Page
    {
        return Page::create([
            'name' => $dto->name,
            'title' => $dto->title,
            'slug' => $dto->slug,
            'meta_keywords' => $dto->metaKeywords,
            'meta_description' => $dto->metaDescription,
            'status' => $dto->status,
        ]);
    }
}
