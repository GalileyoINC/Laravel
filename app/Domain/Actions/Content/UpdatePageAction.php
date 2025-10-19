<?php

declare(strict_types=1);

namespace App\Domain\Actions\Content;

use App\Domain\DTOs\Content\PageUpdateDTO;
use App\Models\Content\Page;

final class UpdatePageAction
{
    public function execute(PageUpdateDTO $dto): Page
    {
        $page = Page::findOrFail($dto->id);
        $page->update([
            'name' => $dto->name,
            'title' => $dto->title,
            'slug' => $dto->slug,
            'meta_keywords' => $dto->metaKeywords,
            'meta_description' => $dto->metaDescription,
            'status' => $dto->status,
        ]);

        return $page;
    }
}
