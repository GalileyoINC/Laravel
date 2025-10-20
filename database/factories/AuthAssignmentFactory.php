<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\AuthAssignment;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\AuthAssignment>
 */
class AuthAssignmentFactory extends Factory
{
    protected $model = AuthAssignment::class;

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
