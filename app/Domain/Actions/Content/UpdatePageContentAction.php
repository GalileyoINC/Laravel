<?php

declare(strict_types=1);

namespace App\Domain\Actions\Content;

use App\Domain\DTOs\Content\PageContentUpdateDTO;
use App\Models\Content\PageContent;

final class UpdatePageContentAction
{
    public function execute(PageContentUpdateDTO $dto): PageContent
    {
        $content = PageContent::findOrFail($dto->id);
        $content->update([
            'content' => $dto->content,
            'status' => $dto->status,
        ]);

        return $content;
    }
}
