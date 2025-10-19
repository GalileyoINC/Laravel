<?php

declare(strict_types=1);

namespace App\Domain\Actions\Podcast;

use App\Domain\DTOs\Podcast\PodcastUpdateDTO;
use App\Models\Content\Podcast;
use Illuminate\Support\Facades\Storage;

final class UpdatePodcastAction
{
    public function execute(PodcastUpdateDTO $dto): Podcast
    {
        $podcast = Podcast::findOrFail($dto->id);
        $podcast->title = $dto->title;
        $podcast->url = $dto->url;
        $podcast->type = $dto->type;

        if ($dto->image) {
            if ($podcast->image && Storage::disk('public')->exists($podcast->image)) {
                Storage::disk('public')->delete($podcast->image);
            }
            $imagePath = $dto->image->store('podcasts', 'public');
            $podcast->image = $imagePath;
        }

        $podcast->save();

        return $podcast;
    }
}
