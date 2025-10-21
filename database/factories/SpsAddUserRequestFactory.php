<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\SpsAddUserRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\SpsAddUserRequest>
 */
class SpsAddUserRequestFactory extends Factory
{
    protected $model = SpsAddUserRequest::class;

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
