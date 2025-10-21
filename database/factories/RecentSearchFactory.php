<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\RecentSearch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\RecentSearch>
 */
class RecentSearchFactory extends Factory
{
    protected $model = RecentSearch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->numberBetween(1, 100),
            'phrase' => $this->faker->optional()->words(2, true), // Shorter phrase
            'id_search_user' => $this->faker->optional()->numberBetween(1, 100),
        ];
    }
}
