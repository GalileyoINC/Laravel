<?php

declare(strict_types=1);

namespace App\Domain\Actions\News;

use App\Domain\DTOs\News\NewsUpdateDTO;
use App\Models\Content\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class UpdateNewsAction
{
    public function execute(NewsUpdateDTO $dto): News
    {
        $news = News::findOrFail($dto->id);

        $data = [
            'name' => $dto->name,
            'title' => $dto->title,
            'meta_keywords' => $dto->metaKeywords,
            'meta_description' => $dto->metaDescription,
            'status' => $dto->status,
            'slug' => Str::slug($dto->name),
        ];

        if ($dto->image) {
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $dto->image->store('news', 'public');
        }

        $news->update($data);

        return $news;
    }
}
