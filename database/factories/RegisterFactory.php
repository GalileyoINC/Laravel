<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\Register;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\Register>
 */
class RegisterFactory extends Factory
{
    protected $model = Register::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'is_unfinished_signup' => $this->faker->boolean(),
        ];
    }
}
