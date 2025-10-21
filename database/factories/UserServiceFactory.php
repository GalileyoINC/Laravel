<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\UserService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\UserService>
 */
class UserServiceFactory extends Factory
{
    protected $model = UserService::class;

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
