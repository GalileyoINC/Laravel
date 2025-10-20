<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Content\NewsContent;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content\NewsContent>
 */
class NewsContentFactory extends Factory
{
    protected $model = NewsContent::class;

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
