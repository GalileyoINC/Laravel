<?php

declare(strict_types=1);

namespace App\Domain\Actions\News;

use App\Domain\DTOs\News\NewsCreateDTO;
use App\Models\Content\News;
use Illuminate\Support\Str;

final class StoreNewsAction
{
    public function execute(NewsCreateDTO $dto): News
    {
        $data = [
            'name' => $dto->name,
            'title' => $dto->title,
            'meta_keywords' => $dto->metaKeywords,
            'meta_description' => $dto->metaDescription,
            'status' => $dto->status,
            'slug' => Str::slug($dto->name),
        ];

        if ($dto->image) {
            $data['image'] = $dto->image->store('news', 'public');
        }

        return News::create($data);
    }
}
