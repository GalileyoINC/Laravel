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
            'id_news' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->randomElement([0, 1]), // STATUS_TEMP or STATUS_PUBLISH
            'params' => $this->faker->optional()->randomElements(['key1' => 'value1', 'key2' => 'value2']),
            'content' => $this->faker->optional()->paragraphs(3, true),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
        ];
    }
}
