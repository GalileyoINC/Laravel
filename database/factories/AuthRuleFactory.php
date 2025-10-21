<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\AuthRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\AuthRule>
 */
class AuthRuleFactory extends Factory
{
    protected $model = AuthRule::class;

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
