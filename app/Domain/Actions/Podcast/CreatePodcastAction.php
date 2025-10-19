<?php

declare(strict_types=1);

namespace App\Domain\Actions\Podcast;

use App\Domain\DTOs\Podcast\PodcastCreateDTO;
use App\Models\Content\Podcast;

final class CreatePodcastAction
{
    public function execute(PodcastCreateDTO $dto): Podcast
    {
        $podcast = new Podcast();
        $podcast->title = $dto->title;
        $podcast->url = $dto->url;
        $podcast->type = $dto->type;

        if ($dto->image) {
            $imagePath = $dto->image->store('podcasts', 'public');
            $podcast->image = $imagePath;
        }

        $podcast->save();

        return $podcast;
    }
}
