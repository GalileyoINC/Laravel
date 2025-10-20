<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\AuthItemChild;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\AuthItemChild>
 */
class AuthItemChildFactory extends Factory
{
    protected $model = AuthItemChild::class;

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
