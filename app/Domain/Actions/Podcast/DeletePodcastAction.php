<?php

declare(strict_types=1);

namespace App\Domain\Actions\Podcast;

use App\Models\Content\Podcast;
use Illuminate\Support\Facades\Storage;

final class DeletePodcastAction
{
    public function execute(Podcast $podcast): void
    {
        if ($podcast->image && Storage::disk('public')->exists($podcast->image)) {
            Storage::disk('public')->delete($podcast->image);
        }

        $podcast->delete();
    }
}
