<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\UserPointSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\UserPointSetting>
 */
class UserPointSettingFactory extends Factory
{
    protected $model = UserPointSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'price' => $this->faker->numberBetween(1, 1000),
        ];
    }
}
