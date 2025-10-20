<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\Mute;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\Mute>
 */
class MuteFactory extends Factory
{
    protected $model = Mute::class;

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
