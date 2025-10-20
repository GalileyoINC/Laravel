<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Content\PageContent;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content\PageContent>
 */
class PageContentFactory extends Factory
{
    protected $model = PageContent::class;

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
