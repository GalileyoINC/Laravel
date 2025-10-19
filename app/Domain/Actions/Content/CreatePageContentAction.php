<?php

declare(strict_types=1);

namespace App\Domain\Actions\Content;

use App\Domain\DTOs\Content\PageContentCreateDTO;
use App\Models\Content\PageContent;

final class CreatePageContentAction
{
    public function execute(PageContentCreateDTO $dto): PageContent
    {
        return PageContent::create([
            'id_page' => $dto->pageId,
            'content' => $dto->content,
            'status' => $dto->status,
        ]);
    }
}
