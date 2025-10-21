<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Content\Podcast;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content\Podcast>
 */
class PodcastFactory extends Factory
{
    protected $model = Podcast::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
